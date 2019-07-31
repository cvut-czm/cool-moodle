<?php


require_once('../../config.php');

$pageurl = new moodle_url('/local/cool/formulax.php');
$PAGE->set_url($pageurl);
$PAGE->set_context(context_system::instance());

$parameters = [
        'test_1' => 5,
        'test_2' => 7.5,
        'test_3' => 9,
        'tests_total' => 21.5
];

echo $OUTPUT->header("");
?>

<table>
    <tr>
        <th>Identifikátor</th>
        <th>Hodnota</th>
    </tr>
    <tr>
        <th>test_1</th>
        <td>5</td>
    </tr>
    <tr>
        <th>test_2</th>
        <td>7.5</td>
    </tr>
    <tr>
        <th>test_3</th>
        <td>9</td>
    </tr>
    <tr>
        <th>tests_total</th>
        <td>21.5</td>
    </tr>
</table>
<form method="post" action="formulax.php">
<textarea id="formulaxeditor" cols="75"  rows="15" name="formula"><?= isset($_POST['formula'])?$_POST['formula']:'' ?></textarea>
    <br/>
    <input type="submit" value="Submit"/>
</form>
<h5>Result</h5>
<?php
if(isset($_POST['formula']))
{
    try {
        $formulas=explode("\n",$_POST['formula']);
        foreach ($formulas as $formula) {
            $formula=trim($formula);
            if(strlen($formula)==0)
                continue;
            $return=preg_match('/^(auto|number|string|boolean|integer)\s+([a-zA-Z0-9_]+)\s*=.+/',$formula,$matches);
            if($return==1) {
                $formula= substr($formula,strpos($formula,'=')+1);
                $formula = \local_cool\cool::get_formula_x($formula);
                \formula_x\driver::set_table($parameters);
                switch ($matches[1]) {
                    case 'auto':
                    case 'string':
                    default:
                        $parameters[$matches[2]] = $formula->getExecutable()->execute()->val();
                        break;
                    case 'number':
                        $parameters[$matches[2]] = $formula->getExecutable()->execute()->val_as_number();
                        break;
                    case 'boolean':
                        $parameters[$matches[2]] = $formula->getExecutable()->execute()->val_as_logical();
                        break;
                    case 'integer':
                        $parameters[$matches[2]] = $formula->getExecutable()->execute()->val_as_integer();
                        break;
                }
            }
            else
            {
                $formula = \local_cool\cool::get_formula_x($formula);
                \formula_x\driver::set_table($parameters);
                echo $formula->getExecutable()->execute()->val();
            }
        }
    } catch (\exception $e)
    {
        echo $e->getMessage();
    }
}
?>
<link href="js/dropdown.css" rel="stylesheet" type="text/css"/>
<script src="js/AutoSuggest.js"></script>
<script src="formulax.js.php"></script>
<?= $OUTPUT->footer() ?>
