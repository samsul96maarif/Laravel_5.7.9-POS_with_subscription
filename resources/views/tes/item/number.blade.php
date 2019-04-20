<!DOCTYPE html>
<html>

  <head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <script type="text/javascript">

      function reverseNumber(input) {
       return [].map.call(input, function(x) {
          return x;
        }).reverse().join('');
      }

      function plainNumber(number) {
         return number.split('.').join('');
      }

      function splitInDots(input) {

        var value = input.value,
            plain = plainNumber(value),
            reversed = reverseNumber(plain),
            reversedWithDots = reversed.match(/.{1,3}/g).join('.'),
            normal = reverseNumber(reversedWithDots);

        console.log(plain,reversed, reversedWithDots, normal);
        input.value = normal;
      }

      function oneDot(input) {
        var value = input.value,
            value = plainNumber(value);

        if (value.length > 3) {
          value = value.substring(0, value.length - 3) + '.' + value.substring(value.length - 3, value.length);
        }
        console.log(value);
        input.value = value;
      }
    </script>
  </head>

  <body>
    <input type="text" onkeyup="splitInDots(this)"/>
    <input type="number" onkeyup="splitInDots(this)"/>

    <input type="number" onkeyup="oneDot(this)"/>
  </body>

</html>
