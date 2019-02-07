export $(cat .env | xargs) 

echo 'Installing puphpeteer'

npm install @nesk/puphpeteer || echo ("failed to install puphpeteer")

echo 'Installing docker chrome'
docker pull browserless/chrome || echo ("failed to pull browserless/chrome")

docker run -p 3000:3000 -e "MAX_CONCURRENT_SESSIONS=5"
    -e "MAX_QUEUE_LENGTH=0"
    -e "PREBOOT_CHROME=true"
    -e "TOKEN=${BROWSERLESS_YOURTOKEN}"
    -e "ENABLE_DEBUGGER=false"
    -e "CONNECTION_TIMEOUT=300000" --restart always browserless/chrome || echo ("failed to start browserless/chrome")