<?php
/**
 * Created by PhpStorm.
 * User: vis
 * Date: 26/11/18
 * Time: 12:49
 */
include 'config.php';

//Свързваме се със сървъра за БД
$conn=mysqli_connect($server,$username,$password);
mysqli_select_db($db);

include 'functions.php';

//Получаваме файловете header.html и footer.html
//PHP кодът е съвместим дори с PHP 4.0

$header = join('', file('header.html'));
$footer = join('', file('footer.html'));
//Показваме заглавието на блога
echo $header;

$left = get_menu() ; // Функция от functions.php



$content = " " ;
if (isset($_GET['p'])) {
    //Получаваме $p и $id
    $p = $_GET['p'];
    $id = $_GET['id'];

    //Ако $p == show, показваме записа
    //Ако $p == filter , показваме записа , отнасящ се до съответната категория . В противен случай показваме съобщението "Wrong Page"
    if ($p == "show") $content = get_blog_record($id);
    elseif ($p == "filter") $content = get_cat($cat);
    else $content = "Wrong page";

}
else {
    //from if (isset($_GET['p])) показваме всички записи и организираме показване на страниците
    $N = 5 ; // Брой записи на страница
    //Изчисляваме броя на записите
    $r1 = mysqli_query("select count(*) as records from blog");
    $f = mysqli_fetch_row($r1);

    //Знаем колко записа има в таблицата
    $rec_count = $f[0];

    //Ако не е указана страница, показваме първата
    if (!isset($_GET['page'])) $page=0;
    else $page = $_GET['page'];

    //Определяме кои коментари да се покажат
    $records = $page * $N ;
    //Заявка показваща записи от $records до $N
    $q= 'select * from blog order by id desc limit'.$records."$N";
    $result = mysqli_query($q);
    $max = mysqli_num_rows($result);

    //Ако страницата не е първа, показваме връзката Back
    if ($page>0) {
        $p = $page - 1 ;
        $content .="<a href='index.php?page=$p'>Back</a> " ;
    }

    $page++ ;

    //Показваме връзката Next
    if ($records+$N < $rec_count)
        $content .="<a href='index.php?page=$page'>Next</a>";


    for ($i=0;$i<$max;$i++)
    {
        $row=mysqli_fetch_array($result);
        $dt = date('d-m-Y H:i',$row[dt]);//Получаваме датата
        //Показваме името на записа и датата на неговото добавяне
        $content .=  "<p><table width='100%'><tr><td bgcolor='#808080' width='100%'><font color='white'>$row[header]</td><td bgcolor='#808080' width='20%'><font color='white'$dt></td></tr></table>";
        //Показваме анонс
        $content .= "<p>$row[anonce]</p>";
            //Показваме връзка Read More, категорията и броя на коментарите
        $cat = get_cat_name($row[cid]);
        $content .=  "<p><table width='100%'><tr><td width='60$'><a href='index.php?p=show$id=$row[id]'>Read More</a></td>
                        <td width='20%'>$cat</td>
                        <td width='20%'>Comments: $row[comments]</td>            
                       </tr></table></p>";






    }
}
//Показваме страницата , $footer
echo "<table border='0' width='100%'>
<tr><td valign='top' width='20%'>$left</td><td valign='top' >$content</td></tr></table>
$footer";
