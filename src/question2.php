<?php
function getStatistics() {
  $data = [];
  $data['users'] = [];
  // 65k rows

  //2 Nested foreach gives complexity of 0(n^2) which is not efficient 
  $allTptp = TariffProviderTariffMatch::all();

  //instead of looping over allTptp->groupBy('user_id') we can shorten the process by skipping over creating multple loops 

  //creating a new variable to map over the switch statement and adding a lamda
  $all = $allTptp->groupBy('user_id')->each(function($value) {

    $one = [];
    $one['name'] = $value[0]->user->first_name . " " . $value[0]->user->last_name;
    $one['valid'] = 0;
    $one['pending'] = 0;
    $one['invalid'] = 0;
    $one['total'] = 0;
    $one['cash'] = 0;

    //using the argument of the lamda, we check that to check the cases of the switch block 
    switch ($value->active_status) {

      case ActiveStatus::ACTIVE: // 1
        $one['valid']++;
        $one['cash'] += floatval(GlobalVariable::getById(GlobalVariable::STANDARDIZATION_UNIT_PRICE)->value);
        break;

      case ActiveStatus::PENDING: // 2
        $one['pending']++;
        break;

      case ActiveStatus::DELETED: // 3
        $one['invalid']++;
        break;

    }

    $one['total']++;
    return $one['cash'] = number_format($one['cash'],2);

  });

  //The result can then be added to the data array
  array_push($data['users'], $all);
  return $data;
}
?>