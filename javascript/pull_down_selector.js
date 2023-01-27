
/* 放送局（selectC）用の配列 */
var item = new Array();

item[0] = new Array();
item[0][0]="---------------------";

/* テレビ */
item[1] = new Array();
item[1][0]="放送局を選択してください";
item[1][1]="NHK";
item[1][2]="フジテレビ";
item[1][3]="日本テレビ";
item[1][4]="TBSテレビ";
item[1][5]="テレビ朝日";
item[1][6]="テレビ東京";
item[1][7]="TOKYO MX";
item[1][8]="abemaTV";
item[1][9]="NHK BS1";
item[1][10]="NHK BSプレミアム";
item[1][11]="BS日本";
item[1][12]="BSフジ";
item[1][13]="BS-TBS";
item[1][14]="BS朝日";
item[1][15]="BSテレビ東京";
item[1][16]="TOKYO MX";
item[1][17]="讀賣テレビ";
item[1][18]="朝日放送テレビ(ABC)";
item[1][19]="毎日放送(MBS)";
item[1][20]="テレビ大阪";
item[1][21]="関西テレビ";
item[1][22]="中京テレビ";
item[1][23]="CBCテレビ";
item[1][24]="東海テレビ";

/* ラジオ */
item[2]= new Array();
item[2][0]="放送局を選択してください";
item[2][1]="ニッポン放送";
item[2][2]="TBSラジオ";
item[2][3]="文化放送";
item[2][4]="TOKYO FM";
item[2][5]="J-WAVE";
item[2][6]="InterFM";
item[2][7]="MBSラジオ";
item[2][8]="ABCラジオ";
item[2][9]="大阪放送";
item[2][10]="CBCラジオ";
item[2][11]="東海ラジオ";
item[2][12]="FM AICHI";
item[2][13]="FM FUJI";


/* Drinks */
item[3] = new Array();
item[3][0]="放送局を選択してください";
item[3][1]="NHK";
item[3][2]="フジテレビ";
item[3][3]="日本テレビ";
item[3][4]="TBSテレビ";
item[3][5]="テレビ朝日";
item[3][6]="テレビ東京";
item[3][7]="TOKYO MX";
item[3][8]="abemaTV";
item[3][9]="NHK BS1";
item[3][10]="NHK BSプレミアム";
item[3][11]="BS日本";
item[3][12]="BSフジ";
item[3][13]="BS-TBS";
item[3][14]="BS朝日";
item[3][15]="BSテレビ東京";
item[3][16]="TOKYO MX";
item[3][17]="讀賣テレビ";
item[3][18]="朝日放送テレビ(ABC)";
item[3][19]="毎日放送(MBS)";
item[3][20]="テレビ大阪";
item[3][21]="関西テレビ";
item[3][22]="中京テレビ";
item[3][23]="CBCテレビ";
item[3][24]="東海テレビ";

/* 放送局のID名 */
var idName="broadcaster";

/* カテゴリーが変更されたら、放送局を生成 */
function createChildOptions(frmObj) {
   /* カテゴリーを変数pObjに格納 */
   var pObj=frmObj.elements["category"].options;

   /* カテゴリーのoption数 */
   var pObjLen=pObj.length;
   var htm="<select name='broadcaster' id='selected'>";
   for(i=0; i<pObjLen; i++ ) {
      /* カテゴリーの選択値を取得 */
      if(pObj[i].selected>0){
         var itemLen=item[i].length;
         for(j=0; j<itemLen; j++){
            htm+="<option value='"+item[i][j]+"'>"+item[i][j]+"<\/option>";
         }
      }
   }
   htm+="<\/select>";
   /* HTML出力 */
   var idxC=frmObj.elements['broadcaster'].selectedIndex;
   document.getElementById(idName).innerHTML=htm;
}

function selectedBroadCast(frmObj) {
   var idxC=frmObj.elements['broadcaster'].selectedIndex;
}
/* 選択されている値をアラート表示 */
function chkSelect(frmObj) {
var s="";
var idxP=frmObj.elements['category'].selectedIndex;
var idxC=frmObj.elements['broadcaster'].selectedIndex;
if(idxP>0){
s+="カテゴリーの選択肢："+frmObj.elements['category'][idxP].text+"\n";
if(idxC > 0){
   s+="放送局の選択肢："+frmObj.elements['broadcaster'][idxC].text+"\n";
}else{
   s+="放送局が選択されていません\n";
}
}else{
s+="カテゴリーが選択されていません\n";
}
alert(s);
}

/* onLoad時にプルダウンを初期化 */
function init(){
htm ="<select name='broadcaster' id='selected' style='width:200px;'>";
htm+="<option value='"+item[0][0]+"'>"+item[0][0]+"<\/option>";
htm+="<\/select>";
/* HTML出力 */
document.getElementById("broadcaster").innerHTML=htm;
}

/* ページ読み込み完了時に、プルダウン初期化を実行 */
window.onload=init;

