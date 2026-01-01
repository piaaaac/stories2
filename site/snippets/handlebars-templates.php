<!-- 
place
  isOpen:         true
  isValidPlace:   true
  isValidTrip:    true
  lat:            45.593496
  lon:            23.938087
  name:           "small town"
  tripLatFrom:    42.979622
  tripLatTo:      45.593496
  tripLonFrom:    23.137259
  tripLonTo:      23.938087
  tripPlaceFrom:  "small town"
  tripPlaceTo:    "small town"
  tripTransport:  car"
-->
<script id="hb-popup" type="text/x-handlebars-template">
  <div class="story-leg-info pb-1 my-1">
    <div class="story-header">
      <span class="location font-ser-m font-weight-600 mr-3 mb-1">{{place.name}}</span>
    </div>
    
    {{#if place.tripPlaceFrom}}
      <p class="m-0 font-sans-s color-grey">From: {{place.tripPlaceFrom}}</p>
    {{/if}}
    {{#if place.tripTransport}}
      <p class="m-0 font-sans-s color-grey">Transport: {{place.tripTransport}}</p>
    {{/if}}
    
  </div>
</script>




<?php /*
<!-- 
ORIGINAL FROM JSUT TRANSITION AS EXAMPLE
story
drivingForcesText
titleIsLink
-->
<script id="hb-popup" type="text/x-handlebars-template">
  <div class="story-prev pb-3 my-3">
    <div class="story-header">
      <span class="number <?= $numberBgClass ?>">{{story.num}}</span>
      <span class="location color-blue-vibrant font-text-s mr-3">{{story.locationText}}</span>
      <!-- <span class="contributor color-grey font-text-s">{{shorten story.contributors 36}}</span> -->
    </div>
    {{#if titleIsLink}}
      <h3 class="title"><a class="color-black font-600" href="javascript:;" onclick="openStory('{{story.id}}');">{{story.title}}<i data-feather="arrow-right" class="icon-adjust"></i></a></h3>
    {{else}}
      <h3 class="title">{{story.title}}</h3>
    {{/if}}
    <p class="font-text-s color-grey">{{drivingForcesText}}</p>
  </div>
</script>
*/ ?>

<!-- handlebars helpers -->
<script>
  // --- via https://stackoverflow.com/a/8206299
  Handlebars.registerHelper('cleanUrl', function(url) {
    url = url || '';
    return url.replace(/(^\w+:|^)\/\//, '');
  });

  Handlebars.registerHelper('parseAndGrab', function(string, prop) {
    return JSON.parse(string).prop;
  });

  Handlebars.registerHelper('join', function(array, separator) {
    return array.join(separator);
  });

  Handlebars.registerHelper('shorten', function(string, max) {
    var str = string;
    if (string.length > max) {
      str = string.substring(0, max);
      var arr = str.split(" ");
      str = arr.slice(0, -1).join(" ") + "…";
    }
    return str;
  });


  // --- via https://stackoverflow.com/a/16315366/2501713
  Handlebars.registerHelper('ifCond', function(v1, operator, v2, options) {
    switch (operator) {
      case '==':
        return (v1 == v2) ? options.fn(this) : options.inverse(this);
      case '===':
        return (v1 === v2) ? options.fn(this) : options.inverse(this);
      case '!=':
        return (v1 != v2) ? options.fn(this) : options.inverse(this);
      case '!==':
        return (v1 !== v2) ? options.fn(this) : options.inverse(this);
      case '<':
        return (v1 < v2) ? options.fn(this) : options.inverse(this);
      case '<=':
        return (v1 <= v2) ? options.fn(this) : options.inverse(this);
      case '>':
        return (v1 > v2) ? options.fn(this) : options.inverse(this);
      case '>=':
        return (v1 >= v2) ? options.fn(this) : options.inverse(this);
      case '&&':
        return (v1 && v2) ? options.fn(this) : options.inverse(this);
      case '||':
        return (v1 || v2) ? options.fn(this) : options.inverse(this);
      default:
        return options.inverse(this);
    }
  });
</script>