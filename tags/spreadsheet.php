<?php
$kirby->set('tag', 'spreadsheet', array(
  'attr' => array(
    'header',
    'class',
    'sheet'
  ),
  'html' => function($tag) {

    $file = $tag->file($tag->attr('spreadsheet'));
    $class = $tag->attr('class');
    $sheet = $tag->attr('sheet');
    $header = $tag->attr('header');
    $header = (is_null($header) || $header === 'true') ? true : false;

    if(! $file) return '';

    $reader = new SpreadsheetReader(kirby()->roots()->content() . DS . $file->uri());

    if($sheet) {
      $sheets = $reader->sheets();
      if($index = array_search($sheet, $sheets)){
        $reader->changeSheet($index);
      }
    }

    if($header) {
      $tablehead = $reader->current();
    }

    $table = new Brick('table', null, array(
      'class' => $class,
    ));

    if(isset($tablehead)) {

      $thead = new Brick('thead');
      $htr = new Brick('tr');

      foreach($tablehead as $colname) {
        $htr->append('<td>' . $colname . '</td>');
      }
      $thead->append($htr);
      $table->append($thead);

    }

    $tbody = new Brick('tbody');

    while ($row = $reader->next()) {
      $btr = new Brick('tr');
      foreach($row as $key => $cell) {
        $btr->append('<td>' . $cell . '</td>');
      }
      $tbody->append($btr);
    }

    $table->append($tbody);

    return $table;
}
));
