<?php
if ($form['datatable']) {
    $datatable = explode(',', $form['datatable']);
    $table = $datatable[0];
    $field = $datatable[1];
    echo CRUDBooster::first($table, ['id' => $value])->$field;
}
if (!empty($form['dataquery'])) {
    $dataquery = $form['dataquery'];
    $query = DB::select($dataquery);
    if ($query) {
        foreach ($query as $q) {
            if ($q->value == $value) {
                echo $q->label;
                break;
            }
        }
    }
}
if ($form['dataenum']) {
    echo $value;
}
?>