<?php
/**
 * Settings file of PHPCSV Guestbook
 * Edit page.php for change appearance.
 * $GBdata parameter for changing entries data file.
 * Please, change $GBadmin and $GBpassword parameters for access to administration page.
 * Change $Titles[HeadTitle] and $Titles[Page] for personal header.
 * You can change $Titles parameters for localization.
 */
$GBdata="gbdb.csv";
$GBadmin="root";
$GBpassword="password";
$GBpagination=10; // عدد الإدخالات في الصفحة الواحدة, 0 - معطل
$GBreadmore=255; // عدد الحروف التي تظهر في المزيد من القراءة, 0 - إظهار كامل النص
$GBsearch=true; // تفعيل أو تعطيل شريط البحث
$GBcaptcha=false; // تفعيل أو تعطيل التحقق كابتشا
$GBupload=array(  // تفعيل أو تعطيل خيارات التحميل
    0=>"images",  // $GBupload=false; - تعطيل
    1=>"pdf",     // $GBupload=true; - تفعيل كل الملفات
    2=>"odt",     // $GBupload=array(); - تفعيل أنواع محددة من الملفات
    3=>"odx",
    4=>"doc",
    5=>"docx",
    6=>"xls",
    7=>"xlsx",
    8=>"txt",
    9=>"zip",
    10=>"rar");
$GBfilesize=1048576; // أقصى حجم ملف
$GBnotificationmailto=""; // ترك فارغة إذا كنت لا تريد إرسال إشعار
$GBnotificationmailfrom="";
$GBtextlenght=7168; // أقصى حجم نص مدخل
$GBcityfield=true; //  تفعيل أو تعطيل حقل المدينة 
$GBlinkfield=true; // تفعيل أو تعطيل حقل الصفحة الرئيسية  
$GBsubjectfield=true; // تفعيل أو تعطيل حقل الموضوع
$GBcategoryfield=array(
    0=>"Public",          // مجموعة الفئات إذا كنت ترغب بتعطليها
    1=>"Help",            // 'Category' field, just leave
    2=>"Special",         // '$GBcategoryfield=false' string
    3=>"Suppot",
    4=>"Order",
    5=>"Other");
$GBstriptags=true; // تمكين أو تعطيل وظيفة tags أثناء إضافة إدخال جديد   
$GBreplies=true; // تمكين أو تعطيل الردود على الرسائل
$GBshownumbers=true; // إظهار أو عدم إظهار عدد من الإدخالات: إذا تم تمكين الردود - لن تظهر الأرقام على أي حال 
$GBstickylocked=true; // تثبيت أو قفل الإدخالات في لوحة الادارة
$GBfield1=true; // تمكين أو تعطيل حقل خاص 1
$GBfield2=true; // تمكين أو تعطيل حقل خاص 2
$GBfield3=true; // تمكين أو تعطيل حقل خاص 3
$Titles["HeadTitle"]="سجل الزوار";
$Titles["Page"]="سجل الزوار";
$Titles["Name"]="اسمك";
$Titles["Required"]="*";
$Titles["City"]="المدينة";
$Titles["Email"]="البريد الإلكتروني";
$Titles["NotPublic"]="لن يتم نشره";
$Titles["Link"]="رابط صفحتك";
$Titles["Text"]="نص الرسالة";
$Titles["Captcha"]="سؤال الأمان والتحقق";
$Titles["CaptchaPlus"]="+";
$Titles["Submit"]="موافق";
$Titles["Added"]="تمت إضافة رسالتك";
$Titles["EmptyName"]="الرجاء كتابة اسمك";
$Titles["EmptyText"]="الرجاء كتابة رسالتك";
$Titles["WrongCaptcha"]="الرجاء كتابة الإجابة الأمنية الصحيحة";
$Titles["From"]="من";
$Titles["Wrote"]="الكاتب";
$Titles["Response"]="رد الإدارة";
$Titles["EmptyFile"]="سجل الزوار فارغ حتى الآن";
$Titles["Login"]="تسجيل دخول المسؤول:";
$Titles["Password"]="كلمة السر:";
$Titles["Enter"]="دخول";
$Titles["WrongLogin"]="خطا في اسم الدخول او في كلمه المرور";
$Titles["AdminHeader"]="إدارة سجل الزوار";
$Titles["AdminExit"]="خروج";
$Titles["AdminHello"]="مرحباً";
$Titles["AdminName"]="الاسم";
$Titles["AdminMessage"]="الرسالة";
$Titles["AdminDate"]="التاريخ";
$Titles["AdminApply"]="تطبيق التغيرات";
$Titles["AdminDeleteChecked"]="حذف المحدد";
$Titles["AdminEdit"]="تحرير";
$Titles["AdminDelete"]="حذف";
$Titles["AdminCancel"]="إلغاء";
$Titles["AdminSureDel"]="هل انت متأكد من الحذف";
$Titles["AdminSureDelMessages"]="الرسائل";
$Titles["MailSubject"]="إدخال جديد في دفتر الزوار الخاص بك";
$Titles["MailAdmin"]="يمكنك تعديل هذه الرسالة أو حذفها أو الرد عليها عبر صفحة المشرف";
$Titles["First"]="الأولى";
$Titles["Last"]="الأخيرة";
$Titles["Previous"]="<<";
$Titles["Next"]=">>";
$Titles["Search"]="بحث";
$Titles["NoResult"]="لا يوجد نتائج بحث";
$Titles["ReadMore"]="قراءة المزيد";
$Titles["FileUpload"]="رفع ملف:";
$Titles["WrongFile"]="يتعذر تحميل الملف";
$Titles["Subject"]="الموضوع";
$Titles["Category"]="التصنيف";
$Titles["About"]="حول";
$Titles["Reply"]="رد";
$Titles["Replied"]="إجابة";
$Titles["Replying"]="الرد على هذه الرسالة:";
$Titles["Locked"]="قفل";
$Titles["Sticky"]="تثبيت";
$Titles["AttachedFile"]="إضافة مرفقات";
$Titles["Field1"]="حقل 1";
$Titles["PreField1"]="<i>";
$Titles["PostField1"]="</i>";
$Titles["Field2"]="حقل 2";
$Titles["PreField2"]="<b>";
$Titles["PostField2"]="</b>";
$Titles["Field3"]="حقل 3";
$Titles["PreField3"]="<i>";
$Titles["PostField3"]="</i>";
?>
