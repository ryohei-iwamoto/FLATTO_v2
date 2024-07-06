document.getElementById("btn_target").onclick = function(){
    // 位置情報を取得する
    if(window.confirm("入力した値をリセットして現在地を出発地点に設定しますか？\n検索する場合は検索ボタンを押してください。")){
      navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }
  };

  // 取得に成功した場合の処理
  function successCallback(position){
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    const form = document.currentlocationsearch;
    console.log(form);
    form.lat.value = latitude;
    form.long.value = longitude;
    form.submit();
  };

  // 取得に失敗した場合の処理
  function errorCallback(error){
    alert("位置情報が取得できませんでした");
  };