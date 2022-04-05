<!DOCTYPE html>
<html lang="en">

<?php
include("header.php");
?>

<body>
  <section id="main-content">
    <section class="wrapper site-min-height">
      <h3><i class="fa fa-angle-right"></i> Activity time</h3>
      <div class="content-panel">
        <h4><i class="fa fa-angle-right"></i></h4>
        <?php
        $select = mysqli_query($con, "SELECT * FROM time_spent where user_id=$user_id");
        while ($s = mysqli_fetch_array($select)) {
          $dur = $s['duration'];
          echo $dur . ' seconds <br>';
        }
        ?>
        <div class="panel-body">
          <figure class="demo-xchart" id="chart"></figure>
        </div>
      </div>
    </section>
  </section>

  </section>





  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="lib/common-scripts.js"></script>
  <script src="lib/xchart/d3.v3.min.js"></script>
  <script src="lib/xchart/xcharts.min.js"></script>


  <script>
    (function() {
      var data = [{
          "xScale": "ordinal",
          "comp": [],
          "main": [{
            "className": ".main.l1",
            "data": [{
              "y": 15,
              "x": "2012-11-19T00:00:00"
            }, {
              "y": 11,
              "x": "2012-11-20T00:00:00"
            }, {
              "y": 8,
              "x": "2012-11-21T00:00:00"
            }, {
              "y": 10,
              "x": "2012-11-22T00:00:00"
            }, {
              "y": 1,
              "x": "2012-11-23T00:00:00"
            }, {
              "y": 6,
              "x": "2012-11-24T00:00:00"
            }, {
              "y": 8,
              "x": "2012-11-25T00:00:00"
            }]
          }],
          "type": "line-dotted",
          "yScale": "linear"
        },
        {
          "xScale": "ordinal",
          "comp": [],
          "main": [{
            "className": ".main.l1",
            "data": [{
              "y": 12,
              "x": "2012-11-19T00:00:00"
            }, {
              "y": 18,
              "x": "2012-11-20T00:00:00"
            }, {
              "y": 8,
              "x": "2012-11-21T00:00:00"
            }, {
              "y": 7,
              "x": "2012-11-22T00:00:00"
            }, {
              "y": 6,
              "x": "2012-11-23T00:00:00"
            }, {
              "y": 12,
              "x": "2012-11-24T00:00:00"
            }, {
              "y": 8,
              "x": "2012-11-25T00:00:00"
            }]
          }],
          "type": "bar",
          "yScale": "linear"
        }
      ];
      var order = [0, 1, 0, 1],
        i = 0,
        xFormat = d3.time.format('%A'),
        chart = new xChart('line-dotted', data[order[i]], '#chart', {
          axisPaddingTop: 5,
          dataFormatX: function(x) {
            return new Date(x);
          },
          tickFormatX: function(x) {
            return xFormat(x);
          },
          timing: 1250
        }),
        rotateTimer,
        toggles = d3.selectAll('.multi button'),
        t = 3500;

      function updateChart(i) {
        var d = data[i];
        chart.setData(d);
        toggles.classed('toggled', function() {
          return (d3.select(this).attr('data-type') === d.type);
        });
        return d;
      }

      toggles.on('click', function(d, i) {
        clearTimeout(rotateTimer);
        updateChart(i);
      });

      function rotateChart() {
        i += 1;
        i = (i >= order.length) ? 0 : i;
        var d = updateChart(order[i]);
        rotateTimer = setTimeout(rotateChart, t);
      }
      rotateTimer = setTimeout(rotateChart, t);
    }());
  </script>
</body>

</html>