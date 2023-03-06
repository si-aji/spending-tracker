"use strict";

/**
 * Formated Moment
 * 
 */
function momentFormated(format, tz = null, date = null){
    if(date === null){
        date = moment().format('YYYY-MM-DD HH:mm:ss');
    }

    if(tz !== null){
        let dateInUtc = moment.utc(date, 'YYYY-MM-DD HH:mm:ss');
        date = dateInUtc.clone().tz(tz);
    }

    return moment(date).format(format);
}

/**
 * Print Indonesia default amount format
 * 
 * @param {*} angka 
 * @param {*} prefix 
 * @returns 
 */
function formatRupiah(angka, prefix = 'Rp', short = false){
    let negative = angka < 0 ? true : false;

    // let balanceHide_state = false;
    // if(balanceHide_state !== null && balanceHide_state === 'true'){
    //     var rupiah = '---';
    //     return prefix == undefined ? rupiah : prefix+" "+rupiah;
    // }

    // Check if short parameter is true
    if(short){
        if(angka < 0){
            angka *= -1;
        }
        rupiah = shortNumber(angka);
    } else {
        angka = Math.round(angka * 100) / 100;

        let split = angka.toString().split('.');
        let decimal = 0;
        if(split.length > 1){
            angka = split[0];
            decimal = split[1];
        }
        var	reverse = angka.toString().split('').reverse().join(''),
        rupiah 	= reverse.match(/\d{1,3}/g);
        rupiah	= rupiah.join('.').split('').reverse().join('');
        if(split.length > 1){
            rupiah += `,${decimal}`;
        }
    }
    
    return `${(prefix == undefined ? `${negative ? '(-' : ''}${rupiah}${negative ? ')' : ''}` : `${prefix} ${negative ? '(-' : ''}${rupiah}${negative ? ')' : ''}`)}`;
}

/**
 * Make All First Character Uppercase
 * 
 * @param {string} str 
 * @returns 
 */
function ucwords(str){
    str = str.toLowerCase().replace(/\b[a-z]/g, (letter) => {
        return letter.toUpperCase();
    });

    return str;
}