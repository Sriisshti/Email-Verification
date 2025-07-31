PHP_PATH=$(which php)
CRON_FILE="src/cron.php"
CRON_LOG="/tmp/xkcd_cron.log"
CRON_JOB="0 0 * * * $PHP_PATH $(pwd)/$CRON_FILE >> $CRON_LOG 2>&1"

(crontab -l 2>/dev/null | grep -v "$CRON_FILE"; echo "$CRON_JOB") | crontab -

echo "CRON job set up to run every 24 hours."
