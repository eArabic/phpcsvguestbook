<?php
/**
 * Main program file of PHPCSV Guestbook
 * See settings.php for configuration.
 * Edit page.php for change appearance.
 * See license.txt for licensing information.
 */
session_start();
include "settings.php";

function SendMail() {
    global $Titles;
    global $GBnotificationmailto;
    global $GBnotificationmailfrom;
    $message=$_POST['name']." ".$Titles[From]." ".$_POST['from']."("
    .$_POST['link'].", ".$_POST['email'].") ".$Titles[Wrote].":\r\n\r\n".$_POST['text']
    ."\r\n\r\n_____\r\n".$Titles[MailAdmin];
    mail($GBnotificationmailto, $Titles[MailSubject], $message,
    "From: ".$GBnotificationmailfrom." \r\n"."Content-type: text/plain; charset=utf-8\r\n"
    ."X-Mailer: PHP/".phpversion());
}

function ReadEntries() {
    global $GBdata;
    global $DataStatus;
    $fhandle=fopen($GBdata,"r") or $DataStatus="empty";
    for($e=0; $entrydata=fgetcsv($fhandle, 16384, ","); $e++) {
        $Entries["$e"]=$entrydata;
        $Entries["$e"][7]=$e+1;
    }
    fclose($fhandle);
    if (!$Entries[0]) $DataStatus="empty";
    return $Entries;
}

function SaveFile() {
    $filename = substr(md5(uniqid()), 0, 13).'.'.pathinfo($_FILES['uploadedfile']['name'], PATHINFO_EXTENSION);
    $uploaddir = 'upload/';
    $uploadfile = $uploaddir.$filename;
    if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $uploadfile)) {
        return $uploadfile;
    } else {
        return false;
    }
}

function CheckFile() {
    global $GBimagesize;
    if (getimagesize($_FILES['uploadedfile']['tmp_name'])) {
        if ((filesize($_FILES['uploadedfile']['tmp_name']))<($GBimagesize)) return SaveFile();
            else return false;
    } else return false;
}

function AddEntry() {
    global $GBdata;
    global $Titles;
    global $PageStatus;
    global $UploadedFile;
    $NewEntry[name]=$_POST['name'];
    $NewEntry[from]=$_POST['from'];
    $NewEntry[link]=$_POST['link'];
    $NewEntry[email]=$_POST['email'];
    if ($UploadedFile) $NewEntry[text]=$_POST['text']." <br><img src=\"$UploadedFile\">";
        else $NewEntry[text]=$_POST['text'];
    $NewEntry[datetime]=time();
    $NewEntry[response]="";
    $fhandle=fopen($GBdata,"a");
    fputcsv($fhandle,$NewEntry);
    fclose($fhandle);
    $PageStatus="added";
    $_SESSION['captcha']="";
}

function AddEntryView() {
    global $Titles;
    global $Values;
    global $PageStatus;
    global $GBcaptcha;
    global $GBtextlenght;
    global $GBupload;
    echo "<h2>",$Titles[Page],"</h2><br>\n";
    if ($PageStatus=="added") echo "$Titles[Added]"; else {
        $captchanumber11=rand(1, 4);
        $captchanumber12=rand(0, 9);
        $captchanumber21=rand(1, 4);
        $captchanumber22=rand(0, 9);
        $_SESSION['captcha']=md5(base64_encode(($captchanumber11.$captchanumber12)+($captchanumber21.$captchanumber22)));
        echo "<form action=index.php method=post enctype=\"multipart/form-data\">\n";
        echo "  $Titles[Name]: <input type=text name=\"name\" value=\"",$Values["name"],"\" maxlength=255> ($Titles[Required])<br>\n";
        echo "  $Titles[City]: <input type=text name=\"from\" value=\"",$Values["from"],"\" maxlength=255><br>\n";
        echo "  $Titles[Link]: <input type=text name=\"link\" value=\"",$Values["link"],"\" maxlength=255><br>\n";
        echo "  $Titles[Email]: <input type=text name=\"email\" value=\"",$Values["email"],"\" maxlength=255> ($Titles[NotPublic])<br>\n";
        echo "  $Titles[Text]:<br>\n  <textarea name=\"text\" wrap=virtual cols=50 rows=5  maxlength=$GBtextlenght>",$Values["text"],"</textarea><br>\n";
        if ($GBupload) {
            echo "  <label for=\"file\">".$Titles[FileUpload]."</label>\n";
            echo "  <input type=\"file\" name=\"uploadedfile\"><br>\n";
        }
        if ($GBcaptcha) echo "  $Titles[Captcha]: <font class=\"text\">$captchanumber11</font><font>$captchanumber11</font><font>$captchanumber12</font> $Titles[CaptchaPlus] <font>$captchanumber21</font><font>$captchanumber22</font><font class=\"text\">$captchanumber21</font> = <input type=text name=\"captcha\" size=2 maxlength=2> ?<br>\n";
        echo "  <input type=submit name=\"submit\" value=\"$Titles[Submit]\">\n";
        echo "</form>\n";
        if ($PageStatus=="emptyname") echo "$Titles[EmptyName]<br>\n";
        if ($PageStatus=="emptytext") echo "$Titles[EmptyText]<br>\n";
        if ($PageStatus=="wrongimage") echo "$Titles[WrongImage]<br>\n";
        if ($PageStatus=="wrongcaptcha") echo "$Titles[WrongCaptcha]<br>\n";
    }
}

function Search($SearchQuery) {
    $Entries=ReadEntries();
    $SearchResultCount=0;
    $SearchResult=false;
    foreach($Entries as $e=>$Entry) {
        for($p=0; $p<7; $p++) {
            if (mb_stristr($Entry[$p],$SearchQuery)) {
                $SearchResult[$SearchResultCount][0]=$e;
                $SearchResult[$SearchResultCount][1]=$Entry;
                $SearchResultCount++;
                break;
            }
        }
    }
    return $SearchResult;
}

function AddSearchBar() {
    global $Titles;
    global $GBsearch;
    if ($GBsearch) {
        echo "<form action=index.php method=post>";
        echo "<input type=text name=\"serachq\" value=\"\" maxlength=255>";
        echo "<input type=submit name=\"search\" value=\"$Titles[Search]\">";
        echo "</form>";
    }
}

function EntriesView() {
    global $Titles;
    global $DataStatus;
    global $Entries;
    global $GBpagination;
    global $GBreadmore;
    if ($DataStatus=="empty") echo "$Titles[EmptyFile]";
        else if($_POST['search']&&$_POST['serachq']) {
            $SearchResult=Search($_POST['serachq']);
            if ($SearchResult) {
                $GBpagination=0;
                unset($Entries);
                foreach($SearchResult as $n=>$Entry) $Entries[$n]=$Entry[1];
            } else echo "$Titles[NoResult]: '",$_POST['serachq'],"'.<br>\n";
        }
        if (($GBpagination>0)&&(count($Entries)>$GBpagination)) {
            $Entries=array_reverse($Entries);
            if ($_GET['page']) switch ($_GET['page']) {
                case $Titles[First]:
                    $CurrentPage=0;
                    break;
                case $Titles[Last]:
                    $CurrentPage=intdiv((count($Entries)-1),$GBpagination);
                    break;
                case "$Titles[Previous]":
                    $CurrentPage=$_SESSION['currentpage']-1;
                    break;
                case "$Titles[Next]":
                    $CurrentPage=$_SESSION['currentpage']+1;
                    break;
                default:
                    $CurrentPage=$_GET['page']-1;
            }
                else $CurrentPage=0;
            for ($e = ($GBpagination*$CurrentPage); $e < ($GBpagination*($CurrentPage+1)); $e++) {
                if ($e>=count($Entries)) break;
                echo "  <div class=\"entry\"><div class=\"messages_header\"><h4>",$Entries[$e][7],". ";
                if ($Entries[$e][2]) echo "<a href=\"",$Entries[$e][2],"\">";
                echo "<b>",$Entries[$e][0],"</b>";
                if ($Entries[$e][2]) echo "</a>";
                if ($Entries[$e][1]) echo " ",$Titles[From]," <b>",$Entries[$e][1],"</b>";
                echo ", ",date("j.m.Y, H:i",$Entries[$e][5]),", ",$Titles[Wrote],":</div></h4><br>\n";
                if ($GBreadmore>0) {
                    $Message=strip_tags($Entries[$e][4]);
                    if (strlen($Message)>$GBreadmore) {
                        $readmorenumber="readmore".$Entries[$e][7];
                        if ($_POST[$readmorenumber]) echo "  ",nl2br($Entries[$e][4]),"<br>\n";
                            else {
                                $Message = substr($Message, 0, $GBreadmore);
                                $Message = substr($Message, 0, strrpos($Message, ' '))."... <form action=\"\" method=\"post\"><button type=\"submit\" name=\"readmore".$Entries[$e][7]."\" value=\"read\" class=\"btn-link\">".$Titles[ReadMore]."</button></form>";
                                echo "  ",nl2br($Message),"<br>\n";
                            }
                    } else echo "  ",nl2br($Entries[$e][4]),"<br>\n";
                } else echo "  ",nl2br($Entries[$e][4]),"<br>\n";
                if ($Entries[$e][6]) echo "<br><i><b>$Titles[Response]:</b><br>\n";
                if ($Entries[$e][6]) echo nl2br($Entries[$e][6]),"</i><br>\n";
                echo "</div><hr>\n";
            }
            echo "<form action=index.php method=\"get\">\n";
            if ($CurrentPage>0) {
                echo "    <input type=\"submit\" value=\"$Titles[First]\" name=\"page\"/>\n";
                echo "    <input type=\"submit\" value=\"$Titles[Previous]\" name=\"page\"/>\n";
            }
            for ($p = ($CurrentPage-2); $p <= ($CurrentPage+2); $p++) {
                $page = $p+1;
                if (($p>=0)&&($p<(count($Entries)/$GBpagination))) {
                    echo "    <input type=\"submit\" value=\"$page\" name=\"page\"";
                    if ($p==$CurrentPage) echo " disabled";
                    echo "/>\n";
                }
            }
            if ($CurrentPage<((count($Entries)/$GBpagination)-1)) {
                echo "    <input type=\"submit\" value=\"$Titles[Next]\" name=\"page\"/>\n";
                echo "    <input type=\"submit\" value=\"$Titles[Last]\" name=\"page\"/>\n";
            }
            echo "</form>\n";
            $_SESSION['currentpage']=$CurrentPage;
        } else {
            $Entries=array_reverse($Entries);
            foreach($Entries as $e=>$Entry) {
                echo "  <div class=\"entry\"><div class=\"messages_header\"><h4>",$Entry[7],". ";
                if ($Entry[2]) echo "<a href=\"$Entry[2]\">";
                echo "<b>",$Entry[0],"</b>";
                if ($Entry[2]) echo "</a>";
                if ($Entry[1]) echo " ",$Titles[From]," <b>",$Entry[1],"</b>";
                echo ", ",date("j.m.Y, H:i",$Entry[5]),", ",$Titles[Wrote],":</div></h4><br>\n";
                if (($GBreadmore>0)&&(!$SearchResult)) {
                    $Message=strip_tags($Entries[$e][4]);
                    if (strlen($Message)>$GBreadmore) {
                        $readmorenumber="readmore".$Entries[$e][7];
                        if ($_POST[$readmorenumber]) echo "  ",nl2br($Entries[$e][4]),"<br>\n";
                            else {
                                $Message = substr($Message, 0, $GBreadmore);
                                $Message = substr($Message, 0, strrpos($Message, ' '))."... <form action=\"\" method=\"post\"><button type=\"submit\" name=\"readmore".$Entries[$e][7]."\" value=\"read\" class=\"btn-link\">".$Titles[ReadMore]."</button></form>";
                                echo "  ",nl2br($Message),"<br>\n";
                            }
                    } else echo "  ",nl2br($Entries[$e][4]),"<br>\n";
                } else echo "  ",nl2br($Entries[$e][4]),"<br>\n";
                if ($Entry[6]) echo "<br><i><b>$Titles[Response]:</b><br>\n";
                if ($Entry[6]) echo nl2br($Entry[6]),"</i><br>\n";
                echo "</div><hr>\n";
            }
        }
}

if($_POST['submit']) {
    if (!$_POST['text']) $PageStatus="emptytext";
    if (!$_POST['name']) $PageStatus="emptyname";
    if ($GBupload) {
        if ($_FILES['uploadedfile']['tmp_name']) {
            $UploadedFile=CheckFile();
            if (!$UploadedFile) {
                $PageStatus="wrongimage";
            }
        }
    }
    if (($_POST['name'])&&($_POST['text']))
        if ($_POST["captcha"]&&(md5(base64_encode($_POST["captcha"]))==$_SESSION["captcha"])) {
            if (!$PageStatus=="wrongimage") {
                AddEntry();
                if ($GBnotificationmailto) SendMail();
            }
        } else if (!$GBcaptcha) {
                if (!$PageStatus=="wrongimage") {
                    AddEntry();
                    if ($GBnotificationmailto) SendMail();
                }
            } else $PageStatus="wrongcaptcha";
    if (($PageStatus)&&!($PageStatus=="added")) {
        $SESSION["value"]["name"]=$_POST['name'];
        $SESSION["value"]["from"]=$_POST['from'];
        $SESSION["value"]["link"]=$_POST['link'];
        $SESSION["value"]["email"]=$_POST['email'];
        $SESSION["value"]["text"]=$_POST['text'];
        $Values=$SESSION["value"];
    } else Unset($SESSION["value"]);
}

$Entries=ReadEntries();

include "page.php";
?>
