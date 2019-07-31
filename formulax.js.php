<?php

require_once('../../config.php');
header('Content-Type: application/javascript');
\local_cool\cool::get_formula_x('');
$values= \formula_x\auto_mapper::map_all();

$parameters = [
    'test_1' => [0,5,10],
    'test_2' => [0,7.5,10],
    'test_3' => [0,9,10],
    'tests_total' => [0,21.5,30]
];
$res="";
foreach ($values as $v=>$k)
    foreach ($k as $kk)
        $res .= "'".$kk."(',";

foreach (['test_1','test_2','test_3','tests_total'] as $kk)
    $res .= "'".$kk."',";
$res=substr($res,0,-1);

?>

const values = [];
const instance = new AutoSuggest({
    caseSensitive: false,
    onChange: function(suggestion) {
        console.log(`"${suggestion.insertHtml || suggestion.insertText}" has been inserted into #${this.id}`);
    },
    suggestions: [
        function($0, callback) {
            const keyword = $0.toUpperCase().split(/\s*[,(+*-/\s]+/);
            const results = [];
            if(keyword.length>0) {
                const data = [ <?= $res ?>];
                const key=keyword[keyword.length-1];

                data.forEach(function (value) {
                    if (value.toUpperCase().indexOf(key) !== -1)
                        results.push(value);
                });
            }
            setTimeout(() => {
                callback(results);
        }, 300);
        }
    ]
}, document.getElementById('formulaxeditor'));
