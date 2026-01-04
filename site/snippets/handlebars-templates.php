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
      <h2 class="font-sans-m font-weight-400 mb-1"><span class="double-dot"></span> {{place.name}}</h2>
    </div>    
    {{#if place.tripPlaceFrom}}
      <p class="m-0 font-sans-s color-grey">&rarr; from {{place.tripPlaceFrom}}</p>
    {{/if}}
    {{#if place.tripTransport}}
      <p class="m-0 font-sans-s color-grey">Transport: {{place.tripTransport}}</p>
    {{/if}}
  </div>
</script>

<!-- 
BOX WITH LEG INFO
place – see above
bars
  transport – number (0-100)
  trip      – number (0-100)
  permanence – number (0-100)
-->
<script id="hb-leginfocontents" type="text/x-handlebars-template">
  <div class="box-wrapper" style="width: 300px;">
    <div class="box my-1">
      <h2 class="font-sans-m font-weight-400 mr-2 mb-2"><span class="double-dot"></span> {{place.name}}</h2>
      {{#if place.tripComments}}
        <p class="m-0 font-sans-s color-grey">{{place.tripComments}}</p>
      {{/if}}
      <div class="font-sans-s">&rarr; from {{place.tripPlaceFrom}}</div>
      <div class="stats mt-2">
        <div class="font-sans-s">Transport: {{place.tripTransport}}</div>
        <div class="bar"><div class="fill" style="width: {{bars.transport}}%;"></div></div>
        <div class="font-sans-s">Trip</div>
        <div class="bar"><div class="fill" style="width: {{bars.trip}}%;"></div></div>
        <div class="font-sans-s">Permanence</div>
        <div class="bar"><div class="fill" style="width: {{bars.permanence}}%;"></div></div>
        <a id="close-leg-button" class="pointer" onclick="navigationAction('close-leg');">&times;</a>
      </div>
    </div>
    <div class="action-buttons">
      <a class="button small green-dark one-of-two" onclick="navigationAction('highlight-prev-leg');">Prev</a>
      <a class="button small green-dark one-of-two" onclick="navigationAction('highlight-next-leg');">Next</a>
    </div>
  </div>
</script>

<!-- 
BOX WITH GENERAL STORY INFO
text – string
name – string
-->
<script id="hb-storyinfocontents" type="text/x-handlebars-template">
  <div class="box-wrapper" style="width: 220px;">
    <div class="box my-1">
      <!-- <h2 class="font-sans-m">{{title}}</h2> -->
      {{#if text}}
        <p class="m-0 font-sans-s color-grey">{{text}}</p>
      {{/if}}
    </div>
    <div class="action-buttons">
      <a class="button small green-dark" onclick="navigationAction('start-story');">Explore {{name}}'s trip</a>
    </div>
  </div>
</script>



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