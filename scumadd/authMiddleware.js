module.exports = function(apiKey) {
  return function(req, res, next) {
    let key = null;

    // Vérifie l'en-tête Authorization (Bearer ou clé brute)
    if (req.headers['authorization']) {
      if (req.headers['authorization'].startsWith('Bearer ')) {
        key = req.headers['authorization'].slice(7);
      } else {
        key = req.headers['authorization'];
      }
    }

    // Vérifie l'en-tête api-key ou le paramètre de requête api_key
    if (!key) key = req.headers['api-key'];
    if (!key) key = req.query.api_key;

    if (key && key === apiKey) {
      return next();
    }

    console.warn('Accès refusé : clé API invalide ou manquante');
    return res.status(401).json({ error: "Clé API invalide ou manquante !" });
  };
};
