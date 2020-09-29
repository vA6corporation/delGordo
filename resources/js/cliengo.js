
window.Cliengo = {}; //all Cliengo configs, functions and params goes into this object

window.Cliengo.chatConfig = {
  "companyId" : "5f0f9cf43b6953002a3d4876",
  "websiteId" : "5f0f9d63e2f723002a86f995",
  "whiteLabelId" : "55df4ed3e4b0337744bcd65a",
  "whiteLabelName" : "",
  "googleAdWordsConversionId" : "",
  "googleAnalyticsId" : "",
  "chatbotName" : "DelGordo",
  "phone" : "",
  "title": "delgordo.com.pe",
  "businessHoursConfiguration" : null,
  "conversionPixels" : "",
  "customFeedbackImageUrl" : "",
  "widgetIcon" : "CLIENGO_ORIGINAL",
   "widgetStyle" : "ICON",
  "chatWindowColor" : "#f5a623",
  "cliengoMeetings" : "false",
  "chatWindowTitle" : "DelGordo",
  "chatWindowInputPlaceholder" : "Escribe un mensaje...",
  "chatInitialMessage" : "Hola, estoy disponible, te puedo ayudar con algo?",
  "chatIsWritingMessage" : " está escribiendo...",
  "chatSaluteTimeInSeconds" : 10,
  "avatarImgUrl": "https://res.cloudinary.com/hbrrdozyj/image/upload/w_35,f_auto,q_auto/v1594858973/cliengo_chatbot_avatar/hnxpb1qjodr8iypx4ign.png",
  "labs":{
        },
  "companyLabs": "socketeer,data_request",
  "planType": "CLIENGO_FREE",
  "vertical": "FOOD_DRINKS",
    "formHooksEnabled" : false,
  "automaticRemarketingEnabled" : true,
  "chatEnabled" : true,
        "poweredBy":{
          "text": "Usamos Cliengo ",
          "link": "https://www.cliengo.com/?utm_source=usamos_cliengo&utm_medium=chat",
          "img":  "https://res.cloudinary.com/hbrrdozyj/image/upload/q_auto,f_auto/v1591102869/logo_cliengo_exygpb.png"
      },
    "customPoweredByUtms": "",
  "softDisable": false,
  "hasConvertedLead" : false,   "CDN_URL_DOMAIN" : "//d47r8x2gwo1do.cloudfront.net",
        "WS_CHATBOT_URL" : "wss://chatbot.cliengo.com",
      "sessionPath" : "/s/5f0f9cf43b6953002a3d4876/5f0f9d63e2f723002a86f995",
  "cacheVersion" : "1438-07062051",
  "debug" : false,
  "whatsAppActive": false,
  "lang" : "es",
  "widgetVersion" : "2.0"
}

var host = 'https://lw.cliengo.com';

var mainClgoScriptId = 'mainclgo';
var mainWspScriptId = 'mainWsp';
var mainclgoScriptExists = document.querySelector('script[id="' + mainClgoScriptId + '"]');

  loadMain();


function loadMain() {
  if(mainclgoScriptExists) {
    console.warn('Cuidado! ya existe un script con id "' + mainClgoScriptId + '", ' +
      'probablemente el script del chat este duplicado en ese sitio!');
  }

  var s = document.createElement('script');
  s.type = 'text/javascript';
  s.id = mainClgoScriptId;
  s.src = host + '/mainclgo.bundle.js';
  console.log(s.src);
  s.setAttribute('data-company-id', '5f0f9cf43b6953002a3d4876');
  s.setAttribute('data-source-id', '5f0f9d63e2f723002a86f995');
  s.setAttribute('data-host', host);
  s.async = true;
  document.body.appendChild(s);
  console.debug('clgo loaded');

  // loadWhatsAppWidget();
}

function onCommonsLoad() {
  console.log(commonsClgoScriptId + ' script loaded successfuly.');
  loadMain();
}

// WhatsApp functions
function setwhatsappConfig() {
  }

function loadWhatsAppWidget() {
  }


