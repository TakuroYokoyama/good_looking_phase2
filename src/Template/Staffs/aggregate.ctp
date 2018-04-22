<html>
<head>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script src="/js/createGraph.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>管理画面：グラフ</title>
<script>
    function getFilterValue(){
        var str = document.getElementById("dataFilter").value;
        $.ajax({
            type: "POST",
            dataType:'json',
            data: {filter: $("#dataFilter").val()},
            url: "/staffs/sortGraph",
         })
        .done(function (data) {
            var labels = [];
            var graphData = [];

            for(var i in data) {
                labels.push(data[i]["name"]);
                graphData.push(data[i]["vote"]);
            };

            createGraph(labels, graphData);
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown) {
            alert("グラフの表示に失敗しました。");
        });
    }
</script>
</head>
<body>
    <div class="filterArea col-xs-2">
        <?= $this->Form->input( "dataFilter", 
                                    ["type" => "select",
                                     "options" => ["desc"  => "得票数：降順",
                                                   "asc"  => "得票数：昇順",
                                                   "man"   => "得票数：性別(男)",
                                                   "woman" => "得票数：性別(女)"
                                                  ],
                                    "id"    => "dataFilter",
                                    "onChange" => "getFilterValue()",
                                    "class" => "form-control"
                                    ]);
        ?>
    </div>
    <div class="graphArea">
    </div>
<script>
    createGraph([<?= $labels ?>], [<?= $graphData ?>]);
</script>
</body>
</html>