/*[
    {"id":1140,"debut":"2012-09-01","fin":"2013-08-31","montant":"20.00"},
    {"id":3607,"debut":"2013-09-05","fin":"2014-08-31","montant":"20.00"},
    {"id":6600,"debut":"2014-09-08","fin":"2015-08-31","montant":"20.00"},
    {"id":8571,"debut":"2015-09-01","fin":"2016-08-31","montant":"20.00"},
    {"id":12036,"debut":"2016-09-05","fin":"2017-08-31","montant":"20.00"},
    {"id":15188,"debut":"2017-09-02","fin":"2018-08-31","montant":"20.00"}
] */

module.exports = (contributions) => {
    return contributions.map( contribution => {
        return {
            id: contribution.id,
            debut: contribution.begin,
            fin: contribution.end,
            montant: contribution.amount.toString()
        }
    });
}