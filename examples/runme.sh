PROJECT_ID=yeggi-344007
gcloud config set project $PROJECT_ID
export GOOGLE_APPLICATION_CREDENTIALS="/home/ealgoo/selfservicekiosk-audio-streaming/examples/yeggi-344007-31d7ab182845.json"
npm --example=yeggi --port=8080 --project_id=$PROJECT_ID run start

