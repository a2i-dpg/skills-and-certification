const displayAlert = function(alert, alerter) {
    let alertMethod = alerter[alert.type];

    if (alertMethod) {
        return alertMethod(alert.message);
    }

    alerter.error("No alert method found for alert type: " + alert.type);
};

const displayAlerts = function(alerts, alerter, type) {
    if (type) {
        // Only display alerts of this type...
        alerts = alerts.filter(function(alert) {
            return type === alert.type;
        });
    }

    for (let a in alerts) {
        displayAlert(alerts[a], alerter);
    }
};

exports.displayAlert = displayAlert;
exports.displayAlerts = displayAlerts;
