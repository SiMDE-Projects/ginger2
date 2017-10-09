//{"login":"jennypau","nom":"JENNY","prenom":"Paul","mail":"pdd@c.fr","type":"etu","is_adulte":true,"is_cotisant":true}


module.exports = (user) => {
    return {
        login: user.login,
        nom: user.lastname,
        prenom: user.firstname,
        mail: user.email,
        type: user.type,
        is_adulte: user.isAdult,
        is_cotisant: user.isContributor,
        badge: user.badge
    };
}