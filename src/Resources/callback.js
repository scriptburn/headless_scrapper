
const blockedResourceTypes = [
    'image',
    'media',
    'font',
    'texttrack',
    'object',
    'beacon',
    'csp_report',
    'imageset',
];

const skippedResources = [
    'quantserve',
    'adzerk',
    'doubleclick',
    'adition',
    'exelator',
    'sharethrough',
    'cdn.api.twitter',
    'google-analytics',
    'googletagmanager',
    'google',
    'fontawesome',
    'facebook',
    'analytics',
    'optimizely',
    'clicktale',
    'mixpanel',
    'zedo',
    'clicksor',
    'tiqcdn',
];


const requestUrl = request._url.split('?')[0].split('#')[0];

 //console.log(request);
  //  request.continue();

 //return request;

if (blockedResourceTypes.indexOf(request.resourceType()) !== -1 )
 {
 
   request.abort();
} else {
    request.continue();
}


