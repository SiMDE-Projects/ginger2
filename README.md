# Ginger next-gen

Disclaimer: ce projet est sujet à changement important à tout moment et est encore en développement.

## Arborescence
* config: Fichiers de configuration
    * ginger: configuration relative à Ginger
    * sequelize: configuration relative à Sequelize
* controllers: Controleurs de l'application
* middlewares:
    * authentication: Authentifie l'utilisateur par sa clé transmise dans l'en-tête Authorization et assigne à req.user l'utilisateur récupéré
    * authorize: Gestion des permissions en fonction des routes. Vérifie si l'ensemble des permissions demandées par la route est disponible chez l'utilisateur
* models: Modèles de l'application
* routes: Routes de l'application (divisées en version puis en ressource)
* seeders: Données de test pour la base de données
* services: Services pour faire le lien entre Controleur et Modèle

Ainsi, on a ici Modèle <-> Service <-> Controleur <-> Route

