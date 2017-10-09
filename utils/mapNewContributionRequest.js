// { "debut": "2017-09-30", "fin": "2018-08-31", "montant": "20.00"}

// { "begin": "2017-08-30", "end": "2017-09-30", "amount": 20}

module.exports = (req) => {
    return {
        begin: req.debut,
        end: req.fin,
        amount: req.montant
    }
}