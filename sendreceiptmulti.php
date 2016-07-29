<style>
th{
padding:5px;
	font-family: Arial Rounded MT Bold,Helvetica Rounded,Arial,sans-serif; 
font-size:40px;
color:white;
Background: navy;
Border: 1px solid silver;
  text-align: center;
border-top-left-radius: 2em 0.5em;
border-top-right-radius: 2em 0.5em;
border-bottom-right-radius: 0em;
border-bottom-left-radius: 0em;}
tr{border-radius:15%;}
td{background: #e8effc;
color:navy;
border-bottom:1px dotted navy;
border-left: 1px solid navy;
padding:4px;
font-family: Verdana,Geneva,sans-serif; 

}
table{border-radius:15%;}
</style>
<?php
include "bsl3.php";
$na = trim($_REQUEST[na]);

echo "<div id='mab'>";
//only payments refering to id not multi
$qpa = $db->query("SELECT * FROM multipaymentstb WHERE mpa_booid = '$na'");
while($ropa = $qpa->fetchArray(SQLITE3_ASSOC) ) 
{ 
echo "id $ropa[pa_id]<br />";
echo "name $ropa[mpa_name]<br />";
$payamountr[] = $ropa[mpa_amount];
echo "amount $ropa[mpa_amount]<br />";
echo "method $ropa[mpa_method]<br />";
echo "ref $ropa[mpa_ref]<br />";
echo "date $ropa[mpa_date]<br />";
echo "booking id $ropa[mpa_booid]<br />";
} 
if(is_array($payamountr) ) { 
$payamount = array_sum($payamountr); } 
$q = $db->query("SELECT * FROM booking WHERE boo_multiaddedid = '$na' AND boo_status != 'COMPLETED'");
while($row = $q->fetchArray(SQLITE3_ASSOC) ) { 

$sname[] = $row[boo_lname];
$tel[] = $row[boo_tel];
$to = $row[boo_tob];
$from = $row[boo_bfrom];
$date1=date_create($to);
$date2=date_create($from);
$diff=date_diff($date1,$date2);
$t = $diff->format("%a");
$length = intval($t);

$guest = $row[boo_guestsadult];
$numguests[] = $row[boo_guestsadult];

$rate = ($guest * $length) * $row[boo_rate];

$tot[] = $rate; 
$acnames[] = $row[boo_acname];
$email[] = $row[boo_email];
$fro[] = $row[boo_bfrom];
$bto[] = $row[boo_tob];
$meals[] = $row[boo_meals];
$ra[] = $row[boo_rate];
$status[] = $row[boo_status];
$fname[] = $row[boo_fname];
$refe[] = $row[boo_gref];
$multibookid[] = $row[boo_multiaddedid];
$spinst[] = $row[boo_spinstruct];
} 
$multiid = $multibookid[0];
$refall = $refe[0];
$btoall = $bto[0];
$fromall = $fro[0];
$emailall = $email[0];
$telall = $tel[0];
$snameall = $sname[0];
$total = array_sum($tot);

$totalnumguests = array_sum($numguests);
$nam = implode(',', $acnames);
$spins = implode(',', $spinst);
$mealsall = implode(',', $meals);
$numunits = count($acnames);
$rateall = (array_sum($ra) / $numunits);
$statusall = $status[0];
$fnameall = $fname[0];
$qta = $db->query("SELECT * FROM salestaxtb");
while($rota = $qta->fetchArray(SQLITE3_ASSOC ) )  { 
$taname = $rota[ta_name];
$taamount = $rota[ta_amount];
$tasy = $rota[ta_currsymbol];
$salestaxtype = $rota[ta_type];
 } 

//only additions to main id
$qt = $db->query("SELECT * FROM addtobilltb WHERE bill_booid = '$na'");

while($ro = $qt->fetchArray(SQLITE3_ASSOC) ) { 
echo "<tr><td>$ro[bill_name]</td>";
echo "<td>$ro[bill_amount]</td></tr>"; 
$addedstot[] = $ro[bill_amount];

} 
if(is_array($addedstot) ) { 
$additions = array_sum($addedstot); } 
$fintot = $total + $additions;
$newtotal = $fintot - $payamount;
echo "$salestaxtype<br />";
$amounttodiv = $taamount + 100;
if($salestaxtype == 'included') { 
$salestax =  round($tot * $taamount/$amounttodiv, 2);} 
else { 
$salestax = round($tot * $taamount/100, 2);
} 

echo "<table cellspacing='0' cellpadding='5'>";
echo "<th colspan='3'>BILL</th>";
echo "<tr><td>" . 'FIRST NAME : ' . "</td><td>$fnameall </td></tr>";
echo "<tr><td>" . 'SURNAME NAME : ' . "</td><td>$snameall</td></tr>";
echo "<tr><td>" . 'TELEPHONE : ' . "</td><td>$telall </td></tr>";
echo "<tr><td>" . 'EMAIL : ' . "</td><td>$emailall </td></tr>";
echo "<tr><td>" . 'FROM : ' . "</td><td>$fromall </td></tr>";
echo "<tr><td>" . 'TO : ' . "</td><td>$btoall</td></tr>";
echo "<tr><td>" . 'ADULT GUESTS : ' . "</td><td>$totalnumguests </td></tr>";
echo "<tr><td>" . 'KIDS : ' . "</td><td>$row[boo_guestskids] </td></tr>";
echo "<tr><td>" . 'PETS : ' . "</td><td>$row[boo_pets] </td></tr>";
echo "<tr><td>" . 'CARS : ' . "</td><td>$row[boo_cars] </td></tr>";
echo "<tr><td>" . 'MEALS : ' . "</td><td>$mealsall </td></tr>";
echo "<tr><td>" . 'RATE : ' . "</td><td>$rateall </td></tr>";
echo "<tr><td>" . 'STATUS : ' . "</td><td>$statusall </td></tr>";
echo "<tr><td>" . 'MULTI ID : ' . "</td><td>$multiid </td></tr>";
echo "<tr><td>" . 'REFERENCE : ' . "</td><td>$refall </td></tr>";
echo "<tr><td>" . 'SPECIAL INSTRUCTIONS : ' . "</td><td>$spins </td></tr>";
echo "<tr><td>" . 'ACCOMMODATION ALLOCATION : ' . "</td><td>$nam</td></tr>";
echo "<tr><td>" . 'DISCOUNT NAME : ' . "</td><td>$row[boo_discountname] </td></tr>";
echo "<tr><td>" . 'DISCOUNT AMOUNT : ' . "</td><td>$row[boo_discountamount] </td></tr>";
echo "<tr><td>" . 'DISCOUNT NUMBER OF GUESTS : ' . "</td><td>$row[boo_discountguests] </td></tr>";
echo "<tr><td>" . 'TOTAL NUMBER OF GUESTS : ' . "</td><td></td><td>$totalnumguests </td></tr>";
echo "<tr><td>" . 'TOTAL OWING WITHOUT ADDITIONS : ' . "</td><td> TAX $salestax</td><td>$total </td></tr>";
echo "<tr><td>" . 'TOTAL OWING WITH ADDITIONS : ' . "</td><td>ADDITIONS $additions</td><td>$fintot </td></tr>";
echo "<tr><td>" . 'BALANCE LESS PAYMENTS : ' . "</td><td> PAYMENTS $payamount</td><td>$newtotal </td></tr>";
echo "</table>";
//select additions to subguests


$qt = $db->query("SELECT booking.boo_acname, booking.boo_id, addtobilltb.bill_name, addtobilltb.bill_amount FROM booking JOIN addtobilltb ON booking.boo_id = bill_booid WHERE addtobilltb.bill_multiaddedid = '$na' AND addtobilltb.bill_booid != '$na'");

while($ro = $qt->fetchArray(SQLITE3_ASSOC) ) { 

$multiacname[] = $ro[boo_acname];

$billtotals[] = $ro[bill_amount];
$indiclientid[] = $ro[boo_id];
 } 
$acnameunique = array_unique($multiacname);
$aclen = count($acnameunique);

$fin = array_unique($indiclientid);
$totalsubguestadditions = array_sum($billtotals);
for ($i=0;$i<($aclen+1);$i++) { 
echo "<table cellspacing='0' border='1' cellpadding='5'>";
echo "<tr><td>$acnameunique[$i] $fin[$i] $na</td><td><a href='subbilldets.php?id=$fin[$i]&&multiid=$na'>View|Print Subbill $acnameunique[$i]</a></td></tr>";
echo "</table>"; } 
echo "<table cellspacing='0' border='1' cellpadding='5'>";
echo "<tr><td>Total all additions </td><td>$totalsubguestadditions</td></tr>";

foreach ($fin as $indi) { 
$qtl = $db->query("SELECT * FROM multipaymentstb WHERE mpa_booid = '$indi'");

while($rol = $qtl->fetchArray(SQLITE3_ASSOC) ) { 

$totalsubguestpayments[] = $rol[mpa_amount]; } } 
$totalsg = array_sum($totalsubguestpayments);
echo "<tr><td>All Payments</td><td>$totalsg</td></tr>";
$finsg = $totalsubguestadditions - $totalsg;
echo "<tr><td>Total of all subguestbills less payments </td><td>$finsg</td></tr></table>";
echo "</div>";
$alltotpay = $finsg + $payamount;
$allamount = $totalsubguestadditions + $fintot;
$alladditions = $additions + $totalsubguestadditions;
$qre = $db->query("SELECT * FROM resourcesem");
echo "<center>";
while($rowre = $qre->fetchArray(SQLITE3_ASSOC) ) { 
echo "<table cellspacing='0' border='1' cellpadding='5'>";
echo "$rowre[res_id]";
$image = $rowre[re_image];
$link1 = $rowre[re_link1];
$link2 = $rowre[re_link2];
$link3 = $rowre[re_link3];
$link4 = $rowre[re_link4];
$linkname1 = $rowre[res_linkname1];
$linkname2 = $rowre[res_linkname2];
$linkname4 = $rowre[res_linkname4];
$emai = $rowre[re_emai];
echo "<tr><td>" . 'IMAGE : ' . "</td><td>$rowre[re_image] </td></tr>";
echo "<tr><td>" . 'LINK 1 : ' . "</td><td>$rowre[re_link1] </td></tr>";
echo "<tr><td>" . 'LINK 2 : ' . "</td><td>$rowre[re_link2] </td></tr>";
echo "<tr><td>" . 'LINK 3 : ' . "</td><td>$rowre[re_link3] </td></tr>";
echo "<tr><td>" . 'LINK 4 : ' . "</td><td>$rowre[re_link4] </td></tr>";
echo "<tr><td>" . 'LINK NAME 1 : ' . "</td><td>$rowre[res_linkname1] </td></tr>";
echo "<tr><td>" . 'LINK NAME 2 : ' . "</td><td>$rowre[res_linkname2] </td></tr>";

echo "<tr><td>" . 'LINK NAME 4 : ' . "</td><td>$rowre[res_linkname4] </td></tr>";
echo "<tr><td>" . 'Our Email : ' . "</td><td>$rowre[re_emai] </td></tr>"; 
echo "</table></center>";
echo "<form action='sendre2.php' method='post'>
<input type='text' name='image' value='$image' /><br />
<input type='text' name='link1' value='$link1' /><br />
<input type='text' name='link2' value='$link2' /><br />
<input type='text' name='link3' value='$link3' /><br />
<input type='text' name='link4' value='$link4' /><br />
<input type='text' name='linkname1' value='$linkname1' /><br />
<input type='text' name='linkname2' value='$linkname2' /><br />
<input type='text' name='linkname4' value='$linkname4' /><br />
<input type='text' name='emai' value='$emai' /><br />
<input type='text' name='name' value='$snameall' /><br />
<input type='text' name='address' value='$address' /><br />
<input type='text' name='phone' value='$phone' /><br />
<input type='text' name='mobile' value='$mobile' /><br />
<input type='text' name='acname' value='$nam' /><br />
<input type='text' name='guestname' value='$guest' /><br />
<input type='text' name='numofguests' value='$totalnumguests' /><br />
<input type='text' name='bfrom' value='$fromall' /><br />
<input type='text' name='tob' value='$btoall' /><br />
<input type='text' name='numdays' value='$length' /><br />
<input type='text' name='total' value='$total' /><br />
<input type='text' name='sum' value='$alladditions' /><br />
<b>Grandtotal</b>
<input type='text' name='grandtotal' value='$allamount' /><br />
<input type='text' name='salestax' value='$salestax' /><br />
<input type='text' name='payments' value='$alltotpay' /><br />
<input type='text' name='guestemail' value='$emailall' /><br />
<textarea rows='20' cols='20' name='notes'></textarea><br />
<input type='submit' name='submit' value='Send' /></ form>"; } 




?>
