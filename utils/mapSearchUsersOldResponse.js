/*[   
    {
    "login": "jenrnertttgparu",
    "nom": "JENNY",
    "prenom": "Paul"
    }, 
    {
    "login": "jgaulgodgamhe",
    "nom": "GAoLDAoMES ",
    "prenom": "Jenony oAndllorea"
    }
]
*/
module.exports = (users) => {
    return users.map( user => {
        return {
            login: user.login,
            nom: user.lastname,
            prenom: user.firstname
        }
    })
}