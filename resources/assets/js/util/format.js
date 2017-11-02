import moment from 'moment';
moment.locale('zh-cn');
function dateFromNow(date) {
    return moment(date, "YYYY-MM-DD HH:mm").fromNow();
}
function test() {
    console.log('testsstse')
}

/**根据经验返回等级等信息
 *
 * @param exp
 */
function sumExp (exp) {
    //每两级之间的经验差值为一个等差数列，差值为500;
    const d = 500;

    /* 当等级为n时，升到第n级所需要的经验S为 n(n-1)d/2
    * n = 1/2 + Math.sqrt( 1/4 + 2S/d);
    * */
    var lv = Math.floor(1/2 + Math.sqrt( 1/4 + 2*exp/d));
    var nextExp = lv*(lv+1)*250 - exp;
    var sunNum = Math.floor(lv/10);
    var moonNum = Math.floor( (lv - 10 * sunNum ) / 5);
    var starNum = lv - 10 * sunNum - 5 * moonNum;
    return {lv:lv, nextExp:nextExp, sunNum:sunNum,moonNum:moonNum,starNum:starNum};
}
export  {dateFromNow,test,sumExp};