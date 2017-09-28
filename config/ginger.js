module.exports = {
    "ginger": {
        "refresh_on_lookup": process.env.GINGER_REFRESH_ON_LOOKUP || false,
        "time_before_update": process.env.GINGER_TIME_BEFORE_UPDATE || 5000,
        "accounts_url": process.env.GINGER_ACCOUNTS_URL || "http://127.0.0.1:3001/ws-picasso/",
        "accounts_useragent": "ginger/0.1",
        "limit_default_search": 10,
        "limit_max_search": 50,
        "key_size": 32
    }
}