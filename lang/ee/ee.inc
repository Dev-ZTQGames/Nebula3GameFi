<?php
/*
 [[언어팩 작업 유의 사항]]

 1. 우선 본 언어팩 작업 유의사항도 함께 번역되어 각 언어팩마다 삽입되어야만 합니다. 반드시 유의사항을 주의깊게 살펴보고 작업을 진행해 주시기 바랍니다.
 2. 언어팩 변수는 절대로 변경되서는 안되며, 해당 변수는 디버깅을 위해 각 언어팩 별로 반드시 같은 라인(line number)에 위치해야만 합니다. ( 같은 행에 같은 변수 및 값 위치 )
 3. 변수는 $lang[]의 배열 형식을 사용하며, 일반적인 작은 따옴표('')로 열고 닫으며, 마찬가지로 상수 값은 쌍따옴표("")를 사용합니다.
 4. 문장의 마지막에는 반드시 세미콜론(;)으로 문장을 마무리해주시기 바랍니다. 변수 시작 및 값 설정 형식을 주위 깊게 확인해 주시기 바랍니다.
 5. 상수값안의 HTML Tag는 절대로 수정하지 않습니다. 해당 태그는 구문 강조를 위한 것으로 태그 안에 대응하는 번역 내용을 기입 바랍니다. ( <br>, <span></span>, <label></label>등 )
 6. {{string}}과 {{number}}가 상수값에 있는 경우는 해당 중복중괄호 블록이 문자열 또는 숫자열로 치환되는 구간입니다. 따라서 해당 중괄호 블록은 절대 수정하지 않습니다.
 7. 언어권에 대한 언어팩별로 콜론, 세미콜론, 따옴표, 마침표, 컴마등의 표현을 반드시 통일해 주시기 바랍니다.

 수정일 : 2022-09-28
 작성자 : 황다해

*/

$lang = array();

$lang['Claw_Machine_Desc_Br']	= "Haara mängus väikseid ja armsaid loomakesi. <br/> Võta osa ka nende igapäevaelust.<br/> Mängime koos kraanamängu!";
$lang['Game_Start']	= "Alusta mängu";
$lang['LogOut']	= "Logi välja";
$lang['AH_Login']	= "Logi sisse Aurora Hunt'i";
$lang['Login_Try_Services']	= "Logige sisse ja nautige meie mitmesuguseid teenuseid!";
$lang['Login']	= "Logi sisse";
$lang['More']	= "veel";
$lang['New']	= "Loo uus";
$lang['Add_My_Game']	= "Lisa minu mängu";
$lang['Casual']	= "Vabamäng";
$lang['Claw_Machine_Desc']	= "Tere tulemast kraanamängu, kus saate püüda väikseid ja armsaid loomakesi ning võtta osa nende igapäevaelust.";
$lang['No_Ongoing_Event']	= "Käimasolevad üritused puuduvad.";
$lang['Preparing']	= "Ettevalmistamisel";
$lang['Ongoing']	= "Käimasolev";
$lang['Login_Required']	= "Palun logige esmalt sisse.";
$lang['Confirm']	= "Kinnita";
$lang['AuroraHunt']	= "AuroraHunt";
$lang['Info_Mail_Change_Code']	= "Saada uus e-posti kinnituskood.";
$lang['Charge']	= "Vaheta";
$lang['My_Marble']	= "Minu Marble'd";
$lang['Select_Recharge_Amount']	= "Laadimise summa valimisel kuvatakse maksesumma.";
$lang['Select_Payment_Method']	= "Palun valige makseviis.";
$lang['Credit_Card']	= "Pangakaart";
$lang['Internet_Banking']	= "Pangaülekanne";
$lang['Virtual_Account']	= "Virtuaalkonto";
$lang['Cultural_Gift']	= "Vautšer";
$lang['Mobile_Phone']	= "Mobiilimakse";
$lang['Charge_Amount']	= "Laadimissumma";
$lang['Payment_Amount']	= "Maksesumma";
$lang['Charge_Marble']	= "Vaheta Marble'id";
$lang['Select_Game']	= "Vali mäng";
$lang['Game_Name']	= "Mängu nimi";
$lang['Enter_Coupon']	= "Sisestage vautšeri kood";
$lang['Coupon_Usage_History']	= "Vautšerite kasutuse ajalugu";
$lang['List_Of_Coupon_Usage_History']	= "Vautšerite kasutuse loend";
$lang['Date_Utc+9']	= "Kuupäev (UTC+9)";
$lang['Event_Contents']	= "Ürituse sisu";
$lang['Compensation_Details']	= "Preemiate üksikasjad";
$lang['Serial_Number']	= "Seerianumber";
$lang['Enter_Coupon_Code']	= "Sisestage vautšeri kood";
$lang['My_Wonder_Money']	= "Minu Wonder Money";
$lang['Free_Charge_Affiliate']	= "Tasuta laadimise sidusettevõte";
$lang['I_Like_Click']	= "ILikeClick";
$lang['My_Game_Cash']	= "Minu mängu Token'id";
$lang['How_To_Use_Precautions']	= "Juhised & abinõud";
$lang['Charge_Guide_List_Num2']	= "Palun sisestage Game Cash'i vahetuse summa.";
$lang['Charge_Guide_List_Num3']	= "Valige 'Vaheta Game Cash'i', et kinnitada Game Cash'i vahetus.";
$lang['Charge_Guide_List_Dot1']	= "Game Cash'i saab vahetada Marble'ite vastu.";
$lang['Charge_Guide_List_Dot2']	= "Juhul, kui Game Cash'i vahetuse summa on suurem kui Minu Marble'd, ei ole võimalik Game Cash'i vahetust teostada.";
$lang['Charge_Guide_List_Dot3']	= "Kui teil ei ole Game Cash'i vahetamiseks piisavalt Marble'id, palun laadige esmalt Marble'id juurde.";
$lang['Enter_Game_Cash']	= "Sisestage Token";
$lang['Charge_Game_Cash']	= "Vaheta Game Cash'i";
$lang['Cash_You_Have']	= "Token'ite jääk";
$lang['Enter_Recipient_ID']	= 'Sisestage saaja ID ja klõpsake "Kinnita ID".';
$lang['Enter_Gift_Amount_Click_Gift_Button']	= 'Sisestage saaja ID, määrake soovitud kogus ning klõpsake "Saada kingitus".';
$lang['Precautions']	= "Teadaanne";
$lang['Give_within_Charge_Limit']	= "Kuna makset töödeldakse kingitusega samaaegselt, piirdub kingitus laetud summaga.";
$lang['History_Of_Sending_Receiving']	= "Kingituste saatmise/vastuvõtmise üksikasju saate vaadata jaotises > Minu info > Kingituste ajalugu.";
$lang['Be_Careful_Using_Gift_Function']	= "Tühistamine pärast kingituse saatmist võib kontopiiranguid tekitada, seega olge kinkimise funktsiooni kasutamisel ettevaatlik.";
$lang['Need_Help_Contact_Customer_Center']	= "Kui teil on saatmisfunktsiooni kohta küsimusi, võtke ühendust kliendikeskusega.";
$lang['Payment_By_Phone']	= "Mobiilimakse";
$lang['Payment_By_Credit_Card']	= "Pangakaardimakse";
$lang['Direct_Debit']	= "Automaatmakse";
$lang['Point_Payment']	= "Punktimakse";
$lang['Overseas_Payment']	= "Välismakse";
$lang['Enter_Information']	= "Sisestage info";
$lang['Recipient_ID']	= "Saaja ID";
$lang['Confirm_ID']	= "Kinnita ID";
$lang['Enter_Again_After_Confirmation']	= "Tuvastamata ID. Palun kinnitage ID ja sisestage uuesti.";
$lang['ID_Verification_Is_Completed'	= "ID edukalt kinnitatud.";
$lang['Gift_Amount']	= "Kingituse summa";
$lang['1_Thou']	= "€1";
$lang['5_Thou']	= "€5";
$lang['10_Thou']	= "€10";
$lang['50_Thou']	= "€50";
$lang['100_Thou']	= "€100";
$lang['Direct_Input']	= "Sisesta summa";
$lang['Gift_Message']	= "Kingituse sõnum";
$lang['Enter_Your_Message']	= "Palun lisage sõnum.";
$lang['Give_Gift']	= "Saada kingitus";
$lang['Cancel']	= "Tühista";
$lang['About_Us']	= "Ettevõtte tutvustus";
$lang['About_Us_Perugia']	= "Oleme tehnoloogia ja kultuurilise kujutlusvõimega Perugia Corporation. Meie põhiväärtusteks on vertikaalne suhtlus ja 'win-win' strateegia. Käivitame ülemaailmse mänguturu platvormi, alustades AuroraHunt'ist. AuroraHunt pakub mitmekesiseid teenuseid ning sisu, nagu loomingut, haridust ja ostlemist, luues maailma mille peategelasteks on kasutajad. AuroraHunt on Perugia mitmekesisuse ja harmoonia nurgakivi. Tagame mängužanritele ja -platvormidele vabaduse ning toetame aktiivselt kõiki oma töid esitlema. Kõik Perugia põhitehnoloogiad ja -teenused on kliendikesksed. Perugia on teiega tulevikus.";
$lang['Perugia_Corporation']	= "Perugia Corporation";
$lang['Head_Office']	= "Peakontor";
$lang['Head_Office_Details']	= "No. 46, 402, Siamdang-ro, Seocho-gu, Seoul";
$lang['Affiliate_Inquiry']	= "Koostöö & päringud";
$lang['Name']	= "Nimi";
$lang['Contact']	= "Telefon";
$lang['Email']	= "E-post";
$lang['Message']	= "Sõnum";
$lang['Send_Message']	= "Saada sõnum";
$lang['Customer_Center']	= "Klienditeenindus";
$lang['Inquiry']	= "Võta meiega ühendust";
$lang['Help_Contact_Us']	= "KKK & päringud";
$lang['My_Inquiry']	= "Minu päringud";
$lang['News']	= "Uudised";
$lang['Notice']	= "Teade";
$lang['Event']	= "Üritused";
$lang['Search_For_Help']	= "Otsi KKK";
$lang['Select_Category']	= "Palun valige kategooria.";
$lang['All']	= "KÕIK";
$lang['Title_Name']	= "PEALKIRI";
$lang['Contents']	= "Sisu";
$lang['Enter_Search']	= "Sisestage otsingutermin";
$lang['Search']	= "Otsi";
$lang['Recommended_Help']	= "Soovitatud KKK";
$lang['How_To_Submit_Inquiry']	= "1:1 klienditeenindus";
$lang['How_To_Submit_Inquiry_Desc']	= "① Valige teenus, millega vajate abi ② Valige päringu kategooriad ③ Sisestage päringu sisu ① Valige teenus, millega vajate abi ② Valige päringu kategooriad ③ Sisestage päringu sisu";
$lang['Language_Selection']	= "Keele valik";
$lang['Language']	= "Keel";
$lang['Developers_List']	= "A-Hunt Developer'id";
$lang['Classification']	= "Kategooria";
$lang['Writer']	= "Autor";
$lang['Account_Name']	= "Konto nimi";
$lang['Date_Of_Creation']	= "Loomise kuupäev";
$lang['Some_Games_Authentication']	= "Mõned Aurora Hunt'i mängud<br/> nõuavad enne mängimist kinnitamist.";
$lang['After_Email_Verification_Is_Complete']	= "Enne jätkamist viige e-posti kinnitamine lõpule.";
$lang['Authentication']	= "Kinnitamine";
$lang['Check_Email_Address']	= "Palun kinnitage liikmeandmetesse sisestatud e-post.";
$lang['Email_Information']	= "E-posti info";
$lang['Check_And_Change_Email_Verification']	= "Kui sisestatud e-post on vale või see pole kasutuses, palun kinnitage ja muutke e-post enne kontrollimist.";
$lang['Check_And_Change_Email']	= "E-posti kinnitamine ja muutmine";
$lang['Verification_Button_Email_Service']	= 'Registreeritud meiliteenusele minemiseks klõpsake allolevale nupule "E-posti kinnitamine".';
$lang['Email_Important_Information']	= "E-post on oluline info, mida kasutatakse tuvastamist nõudvate teenuste jaoks, sealhulgas paroolide leidmiseks.";
$lang['Prevent_Theft_Minimize_Damage']	= "Palun viige e-posti kinnitamine lõpule, et vältida vargusi ja minimeerida e-posti sobimatust kasutamisest tulenevaid kahjusid.";
$lang['Check_Spam_Folder']	= "Kui te ei ole kinnitamismeili kätte saanud, kontrollige oma rämpsposti kasti.";
$lang['Verify_Email']	= "E-posti kinnitamine";
$lang['Email_Verification_Success']	= "Kinnitamine edukalt lõpetatud.";
$lang['Main_Email_Changed']	= "Muutunud e-posti puhul uuendage seda kindlasti oma profiilis ning kinnitage e-post uuesti.";
$lang['Go_Home']	= "Esilehele";
$lang['Ongoing_Event']	= "käimasolev üritus";
$lang['Closed_Event']	= "lõppenud üritus";
$lang['No_Closed_Event']	= "Lõppenud üritused puuduvad.";
$lang['End']	= "Lõppenud";
$lang['My_Inquiry_History']	= "Minu päringud";
$lang['No_Posts_Matching']	= "Kriteeriumidele vastavad postitused puuduvad.";
$lang['New_Post']	= "Uus postitus";
$lang['Login_Required_Go_To_Login']	= "Palun logige esmalt sisse. Kas soovite liikuda sisselogimislehele?";
$lang['Go_To_Lists']	= "Liigu menüüsse";
$lang['Inquiry_Received']	= "";
$lang['Fill_It_Out']	= "<span>*</span> sümbol tähistab kohustuslikke välju.";
$lang['Question_Type']	= "Küsimuste kategooriad";
$lang['Game_Inquiry']	= "Mängu päring";
$lang['Bad_User']	= "Ebameeldiv kasutaja";
$lang['Bug']	= "Bug'id";
$lang['Recovery_Request']	= "";
$lang['Suggestions']	= "Soovitus";
$lang['Payment_Refund']	= "Makse/tagasimakse";
$lang['Enter_Title']	= "Palun sisestage pealkiri.";
$lang['Enter_Contents']	= "Palun sisestage sisu.";
$lang['Inquiry_Title']	= "Päringu pealkiri";
$lang['Inquiry_Contents']	= "Päring sisu";
$lang['Receiving_Inquiry']	= "Päring saadetud";
$lang['Delete_Contents']	= "Kustuta sisu";
$lang['Select_Question_Type']	= "Palun valige küsimuse kategooria.";
$lang['Category selection']	= "Vali kategooria";
$lang['Update']	= "Uuendused";
$lang['Inspection']	= "Vaata üle";
$lang['List_Of_Notice']	= "Teadanded";
$lang['Registration_Date']	= "Regsistreerimiskuupäev";
$lang['Post_Not_Exist']	= "Postitust ei eksisteeri.";
$lang['Rejection_Of_Unauthorized_Email_Collection']	= "Keeldu ilma loata e-posti kogumisest";
$lang['Go_To_Login']	= "Sisselogimislehele";
$lang['Activate_Dormant_Account']	= "Konto lähtestamine";
$lang['Set_Password']	= "Parooli määramine";
$lang['Activate_Account_Setting_New_Password']	= "Konto saab aktiveerida pärast uue parooli määramist.";
$lang['ID']	= "ID";
$lang['Join_Date']	= "Liitumise kuupäev";
$lang['New_Password']	= "uus parool";
$lang['Confirm_New_Password']	= "kinnita uus parool";
$lang['Password_Not_Match']	= "paroolid ei ühti";
$lang['Account_Activation']	= "Aktiveeri konto";
$lang['Cash_Charge_History_List']	= "Cash'i vahetuse ajaloo loend";
$lang['Cash_Charge_History']	= "Cash'i vahetuse ajalugu";
$lang['Enter_Password_Once_More']	= "Isikliku teabe kaitsmiseks sisestage parool uuesti.";
$lang['Enter_Password']	= "Palun sisestage parool.";
$lang['Member_Info']	= "Liikme info";
$lang['My_Info']	= "Minu info";
$lang['Check']	= "Kinnita";
$lang['List_Of_Gift_Details']	= "Kingituste ajaloo loend";
$lang['Gift_Details']	= "Kingituste ajalugu";
$lang['Target']	= "Siht";
$lang['Nickname']	= "Hüüdnimi";
$lang['Change_Password']	= "Parooli muutmine";
$lang['Edit']	= "Redigeeri";
$lang['Authentication_Complete']	= "Edukalt kinnitatud.";
$lang['Unauthorized']	= "Veel kinnitamata";
$lang['List_Of_My_Inquiry']	= "Päringute loend";
$lang['Processing_Status']	= "Staatus";
$lang['No_Inquiry_History']	= "Päringud puuduvad";
$lang['Receiving']	= "Töötlemisel";
$lang['Checking']	= "Kinnitamine";
$lang['Answer_Complete']	= "Vastus kinnitatud";
$lang['Delete']	= "Kustuta";
$lang['Really_wanna_delete']	"Kas olete kindel, et soovite selle kustutada?";
$lang['Edit_Member_Info']	= "Liikme info redigeerimine";
$lang['Email_Verification_Num']	= "E-posti kinnituskood";
$lang['Confirmation_Num']	= "Kinnituskoodi <span> kontrollimine</span>";
$lang['Withdrawal_Of_Membership']	= "Lõpeta liikmelisus";
$lang['Tooltip']	= "Vihje";
$lang['Join_A-Hunt_Developers']	= "Kas valmistute AuroraHunt'is materjali välja andmiseks?<br/>Liituge A-Hunti Developer'siga kohe!";
$lang['Close_Tooltip']	= "Sulge vihje";
$lang['Participation']	= "Liitu";
$lang['Company_Name']	= "Ettvõtte nimi";
$lang['Homepage']	= "Ametlik veebileht";
$lang['Manager']	= "Kontaktisik";
$lang['Choose_Front_Digit']	= "Valige oma telefoninumbri esimesed numbrid";
$lang['Can_Use_A-Hunt_Developers_Board']	= "A-Hunt Developers'i teadetetahvlit saab kasutada pärast ettevõtte nime/ametliku veebilehe/kontaktisiku/telefoninumbri teabe esitamist";
$lang['Available_Nickname']	= "Hüüdnimi on saadaval";
$lang['Nickname_Already_Exists']	= "Hüüdnimi on juba kasutusel";
$lang['Enter_Nickname']	= "Sisestage hüüdnimi";
$lang['Within_20_Characters']	= "Palun sisestage kuni 20 sõna ladina või korea tähestikus.";
$lang['Edition_Of_Member_Info_Completed']	= "Liikmeinfo redigeerimine edukalt lõpetatud.";
$lang['Try_Again_Later']	= "Palun oodake ja proovige uuesti.";
$lang['You_Signed_Up_Same_Email']	= "See e-post on juba kasutuses.";
$lang['Verification_Code_Sending_Text']	= "Kinnituskood saadetud. Kui te ei ole kinnitamismeili kätte saanud, kontrollige oma rämpsposti kasti.";
$lang['Verification_Code_Resending_Text']	= "Uus kinnituskood saadetud. Kui te ei ole kinnitamismeili kätte saanud, kontrollige oma rämpsposti kasti.";
$lang['Check_Verification_Code']	= "Palun kinnitage kinnituskood.";
$lang['Have_Been_Authenticated']	= "Edukalt kinnitatud";
$lang['Confirm_Password_Privacy']	= "Isikliku teabe kaitsmiseks sisestage parool uuesti.";
$lang['Confirm_Password']	= "Kinnita parool";
$lang['Account']	= "Konto";
$lang['Password']	= "Parool";
$lang['Password_Not_Match_Br']	= "Paroolid ei ühti";
$lang['Password_Match_Br']	= "Paroolid ühtivad";
$lang['Password_Incorrect_Try_Again']	= "Teie parool on vale, palun kinnitage parooli ja sisestage see uuesti. ";
$lang['Periodically_Change_Password']	= "Oma isikuandmete kaitsmiseks muutke oma parooli regulaarselt.";
$lang['Alphanumeric_Combinations']	= "Palun sisestage parool (8 kuni 16 ladina tähemärki, parool peab sisaldama numbrit).";
$lang['Available_Passwords']	= "Parool vastab nõuetele";
$lang['Password_Match']	= "Paroolid ühtivad";
$lang['Password_Changed']	= "Parool edukalt muudetud";
$lang['Same_Old_Password']	= "Sisestatud parool on sama, mis eelmine. Palun sisestage uus parool.";
$lang['Please_Confirm_Password']	= "Palun kinnitage parool.";
$lang['Cautions_Canceling_Membership']	= "Märkused liikmelisusest väljaastumise kohta";
$lang['Deleted_Not_Possible_Recover']	= "Pärast liikmelisusest välja astumist kustutatakse AuroraHunt'is kogu teie teave ning seda pole võimalik taastada.";
$lang['Deleted_Game_Info_You_Played']	= "Pärast liikmelisusest välja astumist kustutatakse kogu teie ajalugu.";
$lang['After_Membership_Withdrawal_Desc']	= "Pärast liikmelisusest välja astumist kustutatakse kogu teie mängimise ajalugu ning seda pole võimalik taastada.";
$lang['Refund_Of_Remaining_Cash_Customer_Center']	= "Kui soovite taotleda ülejäänud Token'ite eest raha tagastamist, võtke enne liikmelisuse kustutamise taotlust kindlasti ühendust klienditeeninduskeskusega.";
$lang['Withdraw_Membership_Request']	= "Kui logite sisse 15 päeva jooksul pärast liikmelisusest väljaastumise avalduse esitamist, on teil võimalik väljaastumise avaldus tagasi võtta.";
$lang['Confirm_Account_Cancel_Membership']	= "Liikmelisusest väljaastumiseks kinnitage esmalt enda konto.";
$lang['Refunds_Not_Possible_Agree']	= "Mõistan ja nõustun liikmelisuse lõpetama ilma raha tagasi saamata.";
$lang['Precaution_Canceling_Membership_Agree']	= "Olen liikmelisusest välja astumisel kontrollinud kõiki ettevaatusabinõusid ning nõustun nendega.";
$lang['Confirm_Agreement']	= "Palun kinnitage oma nõusolek.";
$lang['Withdrawal_Membership_Notice']	= "Pärast liikmelisuse lõpetamist kustutatakse kõik AuroraHunti's olevad andmed, \nsamuti nullitakse kõik Marble'd ja Token'id. \nKas soovite kindlasti liikmelisuse lõpetada?\n* Teie andmeid ei taastata.";
$lang['Withdraw_Membership']	= "Lõpeta liikmelisus";
$lang['Re_Enter_Password']	= "Liikmelisuse kustutamiseks sisestage parool uuesti.";
$lang['Withdrawal_Request_Completed']	= "Liikmelisusest väljaastumise taotlus edukalt esitatud.";
$lang['See_You_Again']	= "Täname teid AuroraHunt'i kasutamise eest. Loodame teid taas näha!";
$lang['Log_In_Within_7days']	= "Kui logite sisse 7 päeva jooksul pärast liikmelisusest väljaastumise avalduse esitamist, on teil võimalik väljaastumise avaldus tagasi võtta.";
$lang['Terms_And_Policies']	= "Tingimused & eeskirjad";
$lang['Withdrawal_Membership_Completed_Desc']	= "Teie väljaastumise avaldus on kinnitatud.\\nAvalduse saab tühistada 15 päeva jooksul (avalduse kuupäev kaasa arvatud). Liikmelisusest väljaastumise avalduse tegemine ning taastamine ei ole pärast seda perioodi võimalik. \\n.";
$lang['Info_Verification_Code']	= "Kinnituskood saadetud e-postile";
$lang['Game']	= "Mängud";
$lang['Help']	= "KKK - korduma kippuvad küsimused";
$lang['Join']	= "Registreeru";
$lang['Random_Box']	= "<span>Random<br/>Box</span>";
$lang['Policies']	= "Tingimused";
$lang['Privacy_Policy']	= "Privaatsuspoliitika";
$lang['Youth_Policy']	= "Alaealiste kaitse poliitika";
$lang['Cash_Policy']	= "Token'ite poliitika";
$lang['Perugiacorp']	= "Perugia Corp.";
$lang['Detailed_Address']	= "";
$lang['Registration_Num']	= "";
$lang['Footer_Info']	= "";
$lang['Sub_Charge_Marble']	= "Lae Marble'id";
$lang['Sub_Charge_Game_Cash']	= "Game Cash'i Vahetus";
$lang['Sub_Gift']	= "Kinkimine";
$lang['Coupon_Registration']	= "Registreeri vautšer";
$lang['CTO']	= "Tehnoloogiate juht";
$lang['Web/DB_Developer']	= "Veebi/andmebaasi arendaja";
$lang['Game_Developer']	= "Mänguarendaja";
$lang['Server/Network_Manager']	= "Serveri/võrguhaldaja";
$lang['Game_Service/Operation_PM']	= "Mänguteenuse/tootejuht";
$lang['Game_Operation_GM']	= "Mängujuht";
$lang['Game/Service_Designer']	= "Mängu/teenuse disainer";
$lang['Ctgr_Notice']	= "Teated";
$lang['Find_ID']	= "Otsi ID-d";
$lang['Find_ID_Guide_Message']	= "Sisestage ühe konto kohta üks e-posti aadress.";
$lang['Email_Check']	= "Palun kinnitage enda e-post.";
$lang['Send_Email']	= "E-post saadetud";
$lang['No_Account']	= "Käesoleva e-postiga pole kontot seotud.";
$lang['Fail_Send_Email']	= "E-posti saatmine ebaõnnestus.";
$lang['Find_Password']	= "Unustasin salasõna";
$lang['ID_Check']	= "Palun kinnitage enda konto ID.";
$lang['Not_Match_Info']	= "Sisestatud teave ei ühti.";
$lang['Not_Exist_ID']	= "Sisestatud ID-d ei eksisteeri.";
$lang['Not_Valid_Email']	= "Vigane e-post";
$lang['Set_Nickname']	= "Palun sisestage enda hüüdnimi.";
$lang['Exist_Same_Email ']	= "Selle e-postiga on konto juba registreeritud.";
$lang['Not_Exist_Member_Info']	= "Liikmeinfot ei eksisteeri.";
$lang['Want_Withdrawal_Request']	= "Taotlesite varem liikmelisusest väljaastumist. Kas soovite taotluse tühistada?";
$lang['Withdrawn']	= "Liikmelisusest väljaastumise taotlus on edukalt tühistatud.";
$lang['CapsLock_Activate']	= "Suurtähelukk on sisse lülitatud.";
$lang['Check_Try_Again']	= "Sisestatud ID või parool on vale. Palun proovige uuesti.";
$lang['Naver_Login']	= "Logi sisse Naver'iga";
$lang['Kakao_Login']	= "Logi sisse Kakao'ga";
$lang['Google_Login']	= "Logi sisse Google'ga";
$lang['Facebook_Login']	= "Logi sisse Facebook'iga";
$lang['Apple_Login']	= "Logi sisse Apple'ga";
$lang['Email_Not_Verified']	= "Teie e-post aadress ei ole kinnitatud.\r\nPalun võtke ühendust administraatori/haldajaga.";
$lang['Registration_Complete']	= "Olete edukalt liitunud AuroraHunt'iga.";
$lang['Full_Consent']	= "Nõustu (kõik)";
$lang['Terms_Of_Use_Required']	= "Kasutustingimused";
$lang['Agree']	= "Nõustu";
$lang['Registration_Completed']	= "Teie liitumine oli edukas.";
$lang['Welcome_To_AH']	= "Tere tulemast AuroraHunt'i!";
$lang['Membership_Info']	= "Liikmeinfo";
$lang['Register_Join_Date']	= "Liitumiskuupäev";
$lang['Enter_Registration_Info']	= "Palun sisestage liitumiseks vajalik info.";
$lang['Valid_Message_Name']	= "Palun sisestage kuni 20 sõna ladina või korea tähestikus.";
$lang['Valid_Message_Id']	= "Palun sisestage kombinatsioon 8-16 tähemärgist ladina või korea tähestikus.";
$lang['Choose_Email_Domain']	= "Palun valige e-posti domeen.";
$lang['Registration_Login']	= "Logi sisse";
$lang['Welcome_Registration_Complete']	= "Tere tulemast! Olete edukalt liitunud.";
$lang['Email_Verification']	= "E-posti kinnitamine";
$lang['Enter_Using_Email']	= "AuroraHunt'iga liitumiseks on tarvis aktiivset e-posti kontot.";
$lang['Retransmit_Code']	= "Kinnituskood saadetud";
$lang['Next']	= "Edasi";
$lang['Check_Nickname']	= "Palun kinnitage enda hüüdnimi.";
$lang['Check your email verification']	= "Palun kinnitage e-posti kinnituskood.";
$lang['Valid_Message_Id_Possible']	= "Palun sisestage kombinatsioon 8-16 tähemärgist ladina või korea tähestikus.";
$lang['Signed_Up_Same_Email']	= "Sisestatud e-post on juba kasutuses. Kas soovite liikuda sisselogimislehele?";
$lang['Not_Match_Verification_Code']	= "Sisestatud kinnituskood on vigane.";
$lang['Charge_Guide_List_Num1']	= "Valige Game Cash'i vahetamiseks mäng.";
$lang['MSF']	= "345 viisi deemonikuningaks saamiseks";
$lang['Gift_Method']	= "Kingituse saatmise juhised";
$lang['Authenticate']	= "Kinnita";
$lang['Shortcuts']	= "Liigu Perugia veebilehele";
$lang['Charge_History']	= "Vahetuse ajalugu";
$lang['Usage_History']	= "Kasutusajalugu";
$lang['Done']	= "Lõpetatud";
$lang['Agree_Required_Items']	= "Palun nõustuge tingimustega";
$lang['Available_ID']	= "ID on saadaval";
$lang['Exist_ID']	= "Sisestatud ID on juba kasutuses.";
$lang['Enter_Using_ID']	= "Palun sisestage soovitud ID.";
$lang['Timeout']	= "Aegunud";
$lang['Guide_Reset_PW']	= "Juhised parooli lähtestamiseks";
$lang['Guide_Find_ID']	= "Juhised ID leidmiseks";
$lang['Fail']	= "Ebaõnnestus";
$lang['WonderGame_Charging_Guide']	= "Juhised Wonder Game'i tasuta laadimiskeskuse jaoks";
$lang['Free_Charging_Desc']	= "Mis on tasuta laadimiskeskus? <br/>Laadimiskeskus on Wonder Game'le eksklusiivne täiendus. Pärast CosmosInfra'sse sisselogimist valige laadimiskeskusest sobiv tegevus/ülesanne ning täitke vastav ülesanne. Ülesande täitmisel kantakse Wonder Money kohe teie kontole. <br/><span class='red'>Osasi laadimiskeskuses olevaid tegevusi/ülesandeid ei organiseeri CosmosInfra. Neid tegevusi/ülesandeid juhib ja omab vastutav operaator. Tasulised tegevused/ülesanded võivad olla tasulised, seega palume enne osalemist tutvuda juhistega. </span>";
$lang['How_To_Use_Wonder_Money']	= "Kuidas kasutada Wonder Money't? <br/>Tasuta laadimiskeskuse tegevustes/ülesannetes osalemise kaudu teenitud Wonder Money kantakse kohe Wonder Game'i esimese tegelase seifi. Teenitud Wonder Money't saab kasutada mängude mängimiseks.";
$lang['Wonder_Money_Notice']	= "Wonder Game'i <span class='red'>Wonder Money</span> saab <span class='red'>tasuta laadimiskeskuses juurde laadida</span>. (Marble'd/Token'id v.a). ";
$lang['Contact_Us']	= "Klienditeeninduse/konsultandiga ühenduse loomine";
$lang['Delete_Writing']	= "Kas olete kindel, et soovite kogu sisestatud sisu kustutada? ";
$lang['Division']	= "Erista";
$lang['Cash_Usage_History']	= "Token'ite kasutuse ajalugu";
$lang['Cash_Usage_History_List']	= "Token'ite kasutuse loend";
$lang['Item']	= "Üksus";
$lang['Guide']	= "Kasutusjuhend";
$lang['Terms']	= "Tingimused";
$lang['Account_Login']	= "Konto/Sisse logimine";
$lang['Join_Withdraw']	= "Registreeru/kustuta konto";
$lang['Payment']	= "Makse";
$lang['Etc']	= "Muu";
$lang['Free_Charging']	= "Tasuta laadimiskeskus";
$lang['Phone_Protection_Service']	= "Telefoninumbri kaitse teenus";
$lang['Prevent_Theft_Phone_Num']	= "Telefoninumbri varguse ennetus";
$lang['Withdrawal_Completion']	= "Juhend liikmelisuse lõpetamiseks";
$lang['Cookie_Consent_Popup']	= "Parema kasutaja kogemuse pakkumiseks kasutab see veebisait küpsiseid. Kui jätkate selle veebisaidi kasutamist, nõustute küpsiste kasutamisega.";
$lang['Footer_Text']	= "Perugia Corp. sidevahendajana ei vastuta ühegi mängutehingu ega sellega seotud probleemide eest. Mängutehingud ja muud seonduvad küsimused on teenusepakkujate kohustused.";
$lang['Game_Company_Name']	= "Mängu pakkuja/levitaja nimi";
$lang['Game_Footer_Info']	= "<span>Esindaja: Lee Ho Dong</span><span>TEL: 02-2570-7077 / +886-2-2570-7077</span><span>FAKS: 02-2570-7077 / + 886-2-2570-7077</span><span>E-POST: <a href='mailto:petpointtw1@gmail.com'>petpointtw1@gmail.com</a></span>";
$lang['Platform_Rating']	= "Platvormi reiting";
$lang['Platform_Compatible_Device']	= "Platvormiga ühilduvad seadmed";
$lang['Not_Available_Youth']	= "Materjal ei sobi alaealistele";
$lang['Rating_12']	= "Sobilik alates 12. eluaastast";
$lang['Rating_15']	= "Sobilik alates 15. eluaastast";
$lang['ISO_Icon_No_Output']	= "iso ikoon puudub väljund"
$lang['ISO_Icon_Output']	= "iso ikoon väljund"
$lang['Hashtag']	= "Hashtag";
$lang['Game_Introduction']	= "Mängu tutvustus";
$lang['Game_Company_Address']	= "Ettevõtte aadress";
$lang['Game_Company_Business_Num']	= "Ettevõtte maksukohustuslase number";
$lang['Game_Company_CEO']	= "Ettevõtte esindaja";
$lang['Game_Company_Security_Officer']	= "Ettevõtte turvaesindaja";
$lang['Game_Company_Tel']	= "Ettevõtte kontaktnumber";
$lang['Game_Company_Fax_Num']	= "Ettevõtte faksinumber";
$lang['Game_Code']	= "Mängukood";
$lang['Sub_Banner_List']	= "Bänneriloend";
$lang['Web']	= "Veebileht";
$lang['Android']	= "Android";
$lang['Rating_All']	= "Sobilik igale vanusele";
$lang['Play_Game']	= "Alusta mängu";
$lang['Mining_Maze_Desc']	= "Leidke tee läbi lookleva labürindi! Kas leiate väljapääsu?! <br/>";
$lang['NEDACOLONY_Desc']	= "Asusta uus taevatäht armsate tegelastega maailmas, mis on täis imesid ja lusti.";
$lang['Metaverse']	= "METAVERSUM";
$lang['Coming_Soon']	= "Varsti tulekul";
$lang['CEO']	= "Esindusdirektor";
$lang['Fax_Num']	= "Faksinumber";
$lang['Rating_All_Stc']	= "Sobilik igale vanusele";
$lang['Rating_12_N']	= "Sobilik alates 12. eluaastast";
$lang['Rating_15_N']	= "Sobilik alates 15. eluaastast";
$lang['Not_Available_Youth']	= "Materjal ei sobi alaealistele";
$lang['Mail01_Text1']	= "Tervitused AuroraHunt'ilt!;"
$lang['Mail01_Text2']	= "Täname teid siiralt AuroraHunt'i kasutamise eest. <br> Registreerumiseks vajalik e-posti kinnituskood on järgmine:";
$lang['Auth_Code_Num']	= "Kinnituskood";
$lang['Mail01_Text3']	= "See e-kiri on ainult väljaminevaks kasutuseks. <br> Kui teil on AuroraHunti kohta küsimusi, klõpsake AuroraHunti klienditeeninduskeskuse külastamiseks allolevat nuppu: <a href='https://{{url}}/ sub/cs_help_list.htm' target='_blank' style='margin: 0;padding: 0;border: 0;outline: 0;font-weight: inherit;font-style: inherit;vertical-align: baseline;-moz -box-sizing: border-box;box-sizing: border-box;text-decoration: none;'> <button style='margin: 0;padding: 0;border: 0;outline: 0;font-weight: 600;font-style: inherit;vertikaalne joondus: baasjoon;-moz-box-sizing: border-box;box-sizing: border-box;color: #ffff;border-bottom: 1px solid #fff;'>AuroraHunt'i klienditeenindus</button></a>";
$lang['Mail02_Registration_Complete']	= "Registreerimine oli edukas";
$lang['Mail02_Text1']	= "Tervist! <b style='color: #222;font-weight: 600;'>{{name}}</b><br>Kirjutame teile AuroraHunt'ist.";
$lang['Mail02_Text2']	= "Täname teid siiralt AuroraHunt'i kasutamise eest!";
$lang['Mail02_Text3']	= "<b style='color: #222;font-weight: 600;'>Olete edukalt liikmeks registreerunud e-posti aadressiga: ({{email}}).</b><br>Kasutage oma kontot, et vabalt kasutada kõiki AuroraHunt'i poolt pakutud teenuseid.";
$lang['Mail02_Text4']	= "Liikme infot saab muuta liikme info redigeerimise lehel.";
$lang['Thank_You']	= "Täname teid!";
$lang['Mail03_Text1']	= "Edastame teile liikmestaatusest loobumiseks vajaliku e-posti kinnituskoodi.";
$lang['Mail04_Text1']	="Teavitame teid meiega registreeritud AuroraHunt'i ID'st. <br>Teie AuroraHunt'i ID on: <b style='color: #222;font-weight: 600;'>{id}}</b>.";
$lang['Target_ID']	= "Sihi ID";
$lang['Mail05_Text1']	= "Teie taotletud ajutine parool on edukalt välja antud. <br>Teie ajutine parool on järgmine:";
$lang['Temporary_PWD']	= "Ajutine parool";
$lang['Mail05_Text2']	= "Teie parool on lähtestatud. <br>Pärast ajutise parooliga sisselogimist määrake koheselt uus parool.";
$lang['Dormant_Account_Guide']	= "Ajutiselt deaktiveeritud kontode aktiveerimine";
$lang['Mail06_Text1']	= "Te pole mõnda aega <b style='color: #222;font-weight: 600;'>AuroraHunt'i</b> sisse loginud. ";
$lang['Mail06_Text2']	= "Kui te ei logi sisse <b style='color: #222;font-weight: 600;'>AuroraHunti</b> ühe kuu jooksul pärast selle e-kirja saamist, <br> teie konto deaktiveeritakse ajutiselt." ;
$lang['Expected_Date_Dormant_Account']	= "Konto deaktiveerimise planeeritud kuupäev:";
$lang['Separate_Personal_Info']	= "Eraldi hoiustatud <br>privaatsus üksikasjad";
$lang['Account_Member_Info']	= "AuroraHunt'i konto ja liikmeinfo";
$lang['Mail06_Text3']	= "Vastavalt teabe- ja sidevõrkude kasutamise ja teabekaitse edendamise seaduse artiklile 29 ja sama seaduse jõustamismääruse artiklile 16 muudab AuroraHunt kontod, mis pole sisse loginud <b style='color : #222;font-weight: 600;'> AuroraHunt'i poolt määratud aja jooksul puhkeolekusse ning hoiustab kasutaja isiklikke andmeid eraldi.";
$lang['Mail06_Text4']	= "Deaktiveeritud kontosid saab <b class='red' style='color: #d22c26;font-weight: 600;'>uuesti aktiveerida alates deaktiveerimise kuupäevast</b>.";
$lang['Canceling_Dormant_Account']	= "Teave deaktiveeritud kontode taasaktiveerimise kohta";
$lang['Mail07_Text1']	= "Täname teid väga, et AuroraHunt'i uuesti külastate! <br>Selle sisselogimisseansi seisuga on teie konto uuesti aktiveeritud ja teie eraldi salvestatud isikuandmed integreeritakse uuesti teie kontoga.";
$lang['Return_Completion_Date']	= "Taasliitumise kuupäev";
$lang['Mail07_Text2']	= "Oma isikuandmete aegumiskuupäeva saate muuta jaotises Minu leht -> Isikuandmete haldamine.";
$lang['Mail07_Text3']	= "Kui kahtlustate vargusjuhtumit, võtke koheselt ühendust klienditeeninduskeskusega.";
$lang['Mail08_Text1']	= "Täname teid väga AuroraHunt'i kasutamise eest! <by>E-posti aadressi muutmiseks vajalik kinnituskood on järgmine:";
$lang['Completion_Membership_Withdrawal']	= "Teade seoses teie liikmelisuse lõpetamise taotlusega";
$lang['Mail09_Text1']	= "Teavitame teid, et teie liikmelisuse lõpetamise avaldus on edukalt täidetud. <br/>Taotletud avaldus on hetkel menetlemisel. <br/>Teie avalduse üksikasjad on järgmised:";
$lang['Target_Email']	= "Sihi e-post";
$lang['Application_Withdrawal_Date']	= "Liikmelisuse lõpetamise taotluse kuupäev";
$lang['Mail09_Text2']	= "Pärast liikmelisuse lõpetamise protsessi kustutatakse teie mänguteave, liikmelisuse teave ja kogu teie edastatud ja/või loodud isiklik teave. Nende andmete taastamine ei ole võimalik.";
$lang['Mail10_Text1']	= "Teavitame teid, et teie liikmelisuse lõpetamise taotlus on edukalt menetletud.";
$lang['Withdrawal_Date']	= "Liikmelisuse lõpetamise kuupäev";
$lang['Mail10_Text2']	= "Täname teid, et kasutasite AuroraHunt'i!";
$lang['Mail10_Text3']	= "AuroraHunt pühendub edasistele arengutele.";
$lang['Create_CAT_Wallet']	= "CAT - Create Wallet [Go]";
$lang['EA']	= "EA";
$lang['CreateCATWalletDone']	= "CAT wallet has been created.";
$lang['GetBack']	= "Back";
$lang['NeedNFTMsg']	= "Sorry, you have no NFT. so can not play^^";
$lang['NeedCATWallet']	= "Please create a CAT wallet from Bavi Wallet.";
$lang['CopyRightText']	= "Since Copyright 2022. CosmosInteractive Co., Ltd. Perugia Corp. All rights reserved.";
$lang['CopyDone']	= "Copied successfully.";
$lang['MM_ChangeToMainNet']	= "Please set the Metamask Wallet Network to Main Net.";
$lang['MM_Custom_Sync']	= "AuroraHunt is optimized for Metamask. Please use Metamask or sync your wallet address directly.";
$lang['MM_Sync_MoveToPage']	= "Metamask is not supported in the environment or it is not installed. Please install a metamask or sync your wallet address directly in the case of an environment where it is not supported.\\n\\nCancel: Synchronize wallet address\\nConfirm: Go to Metamask page";
$lang['PleaseMakeCATWallet']	= "For synchronize the NFT information you purchased, Please create a CAT wallet to run the game.";
$lang['PleaseSyncAccountAddress']	= "Please sync your Account address.";
$lang['My_Referral'] = "";
$lang['ReferralSystem'] = "";
$lang['ReferralCode'] = "";
$lang['ReferralLink'] = "";
$lang['ReferralNumber'] = "";
$lang['Verify'] = "";
$lang['Referral_Success'] = "";
$lang['Referral_Failure'] = "";
$lang['Referral_Verified_done'] = "";
$lang['Referral_Code_incorrect'] = "";
$lang['Meta_Keyword'] = "ASHA, Astrogaz, AuroraHunt, BALOO, BAVI Wallet, Plokiahel, Laud, Casual, Casual mäng, CAT Coin, CAT Token, Catch! The Friends, CATY, Claw Machine, Claw Machine - Catch! The Friends -, Claw Machine: Catch! The Friends, Claw Machine: NFT, claw-machine-catch-the-friends, clwmc, Krüpto, ED, EN, Inglise keel, Game Cash, Mängu platvorm, Mängusait, GameFi, Game-Fi, Mängu NFT, Kogupere mäng, HARU, HTML 5, Jaapan, JP, KO, Korea keel, KORT, Vaba aeg , LEO, Marble, Metaversum, Metaversumi mäng, MICA, Minimäng, Mining Maze, mm, Mobiilimäng, NEDA Colony, NFT, NFT turuplats, NFT turg, Node NFT, NOMI, Nova Hatcher, P2E, PC mäng, Arvutimäng, Perugia, Perugia coperation, Perugiacorp., Petpoint Aasia, Photon Milky Way, Photon Space, Photon-Milkyway, Photon-Space, Mängi teenimiseks, PMW, PSC, PSEHV, Pusle, Random Wiki Box, Taiwan, TINA, Token, TW, US, USA, Viet Nam, VN, Veebimäng, Virtuaalne vara, Krüptoraha, Mäng, Mängu sait, Mänguplatvorm, Mänguraha, Globaalne, NEDA Colony, Node, Node NFT, Nomi, Nova Hatcher, Taiwan, Raha teenimise ilm, Leo, Vaba aeg, Marble, Mining Maze, Metaversum, Metaversumi mäng, Mobiilimäng, Mobiili mäng, USA, Minimäng, Mini mäng, Labürindi lahendamine, Mica, Baloo, BAVI Wallet, Vietnam, Lauamäng, Plokiahel, Sotsiaalne kasiino, Steam, Asha, Astrogaz, Rakenduste pood, Ed, UK, AuroraHunt, Online mäng, Veebimäng, Kraanamäng, Jaapan, Kõik vanusegrupid, Kõik vanusegrupid mäng, Casual, Casual mäng, Casual, Casual mäng, CAT Coin, CAT Token, CAT Coin, Cat Token, Caty, Kort, Tina, Pusle , Puslemäng, Perugia, Perugia Corporation, PetPoint Aasia, Haru, Lõuna-Korea";
$lang['Meta_Desc'] = "AuroraHunt on globaalne mänguplatvorm, mis tegutseb Veeb 2 ja ja Veeb 3 valdkondades, pakkudes online, veebipõhiseid ja mobiilseid P2E elamusi.";
$lang['Sync_Account_Desc'] = "";
$lang['Sync_Wallet_Addr'] = "";
$lang['Locale'] = "et_EE";
$lang['URL'] = "https://ee.aurorahunt.xyz/";
$lang['Block_msg']				= "Teie konto on blokeeritud.\\n Ooteaeg:";
?>