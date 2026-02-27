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
      <p class="m-0 font-sans-s color-grey">
        &rarr; from {{place.tripPlaceFrom}}
        {{#if place.tripTransport}}
          by {{place.tripTransport}}
        {{/if}}
      </p>
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
stats
  tripDays - number
  stayDays - number
  noTripData - number
-->
<script id="hb-leginfocontents" type="text/x-handlebars-template">
  <div class="box-wrapper" style="width: 300px;">
    <div class="box my-1">
      <h2 class="font-sans-m font-weight-400 mr-2 mb-2"><span class="double-dot"></span> {{place.name}}</h2>

      <div class="font-sans-s mb-2">
        Traveled
        {{#if place.tripTransport}}
          by {{place.tripTransport}}
        {{/if}}
        from {{place.tripPlaceFrom}}
      </div>

      {{#if place.tripComments}}
        <p class="m-0 font-sans-s color-grey">{{place.tripComments}}</p>
      {{/if}}

      <div class="stats mt-2">
        <!--  
          <div class="font-sans-s">Transport: {{place.tripTransport}}</div>
          <div class="bar"><div class="fill" style="width: {{bars.transport}}%;"></div></div>
          -->
        <!-- <div class="bar"><div class="fill" style="width: {{bars.trip}}%;"></div></div> -->
        
        <div class="font-sans-s">
          {{#if stats.noTripData}}
            Unknown travel duration
          {{else}}          
            {{stats.tripDays}} {{pluralize stats.tripDays "day" "days"}} traveling
          {{/if}}
          {{#if stats.stayDays}}
            , {{stats.stayDays}} {{pluralize stats.stayDays "day" "days"}} permanence
          {{/if}}
          
        </div>
        
        <div class="trip-symbols mt-2" data-style="small">
          {{#repeat stats.tripDays}}
            <span class='tr'></span>
          {{/repeat}}
          {{#repeat stats.noTripData}}
            <span class='tr-nodata'></span>
          {{/repeat}}
          {{#repeat stats.stayDays}}
            <span class='st'></span>
          {{/repeat}}
        </div>

        <a id="close-leg-button" class="pointer" onclick="navigationAction('close-leg');">&times;</a>
      </div>

      <div class="action-buttons mt-4 mb-1">
        <a class="button small green-dark grey-light one-of-two" onclick="navigationAction('highlight-prev-leg');">Prev</a>
        <a class="button small green-dark grey-light one-of-two" onclick="navigationAction('highlight-next-leg');">Next</a>
      </div>

    </div>
    
    <!--  
    <div class="action-buttons">
      <a class="button small green-dark one-of-two" onclick="navigationAction('highlight-prev-leg');">Prev</a>
      <a class="button small green-dark one-of-two" onclick="navigationAction('highlight-next-leg');">Next</a>
    </div>
    -->
  </div>
</script>

<!-- 
BOX WITH GENERAL STORY INFO
text – string
quote – string
name – string
-->
<script id="hb-storyinfocontents" type="text/x-handlebars-template">
  <div class="box-wrapper" style="width: 220px;">
    <div class="box my-1">
      <!-- <h2 class="font-sans-m">{{title}}</h2> -->
      {{#if text}}
        <p class="m-0 font-sans-s">{{text}}</p>
      {{/if}}
      {{#if quote}}
        <p class="m-0 mt-2 font-sans-s">{{quote}}</p>
      {{/if}}
    </div>
    <div class="action-buttons">
      <a class="button small green-dark" onclick="navigationAction('start-story');">Explore {{name}}'s trip</a>
    </div>
  </div>
</script>



<!-- handlebars helpers -->
<script>
  Handlebars.registerHelper('pluralize', function(count, singular, plural) {
    return count === 1 ? singular : plural;
  });

  // If count = 5, this outputs five <span> elements, with this equal to the index (0–4).
  // {{#repeat count}}
  //   <span>{{this}}</span>
  // {{/repeat}}
  Handlebars.registerHelper('repeat', function(n, block) {
    let out = '';
    for (let i = 0; i < n; i++) {
      out += block.fn(i);
    }
    return out;
  });

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