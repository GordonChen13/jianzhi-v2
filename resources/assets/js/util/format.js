import moment from 'moment';
moment.locale('zh-cn');
function dateFromNow(date) {
    return moment(date, "YYYY-MM-DD HH:mm").fromNow();
}
function test() {
    console.log('testsstse')
}
export  {dateFromNow,test};