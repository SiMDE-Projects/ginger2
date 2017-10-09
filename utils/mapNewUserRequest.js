module.exports = (user) => {
    let u = {
        login: user.login,
        lastname: user.nom,
        firstname: user.prenom,
        email: user.mail,
        type: user.type,
        isAdult: user.is_adulte,
        isContributor: user.is_cotisant,
        badge: user.badge
    };
    // On enlève les propriétés undefined
    Object.keys(u).forEach(key => u[key] === undefined && delete u[key])
    return u;
}