var video = document.getElementById('mov');
var prevFrame = document.getElementById('prev-frame');
var nextFrame = document.getElementById('next-frame');
let showAll = document.getElementById('show-all');
let showFrame = document.getElementById('show-frame');
let showMax = document.getElementById('show-max');
let sendArr = document.getElementById('send-arr');
let showProgression = document.getElementById('show-progression');
let showTime = document.getElementById('show-time');
let fps = 30;
var frameRate = 1/fps;
let frameNum = 0;
let parts = JSON.parse(data);
let part;
let all_coordinate = {};
let full_coordinate = [];
let deletePoints = [];
let pointSize = 8;
sendArr.disabled = false;
let progressBar = document.getElementById('progress-bar');

// 表初期化
function resetTable(){
  for (let i = 0; i <  parts.length; i++) {
    let newRow = showAll.insertRow();

    let newCell = newRow.insertCell();
    let newText = document.createTextNode(i+1);
    newCell.appendChild(newText);

    newCell = newRow.insertCell();
    newText = document.createTextNode(parts[i]);
    newCell.appendChild(newText);

    newCell = newRow.insertCell();
    newText = document.createTextNode('');
    newCell.appendChild(newText);
  };
};

resetTable();

video.onloadedmetadata = function(){
  const canvas = document.getElementById('canvas');
  let maxFrame = Math.trunc(video.duration*fps);
  showMax.textContent = maxFrame;
  showFrame.textContent = frameNum;
  showProgression.textContent = Math.round(frameNum / maxFrame * 100);
  showTime.textContent = Math.round(frameNum * frameRate * 1000);

  let width = video.videoWidth;
  let originalHeight = video.videoHeight;
  let aspectRatio = video.videoWidth / video.videoHeight;
  let sizeRatio =video.videoHeight / video.clientHeight;


  console.log('size：'+video.style.width+'*'+video.style.height);

  let count = 0;

  const ctx = canvas.getContext('2d');
  canvas.style.left = video.getBoundingClientRect().left;
  canvas.style.top = video.getBoundingClientRect().top;
  canvas.width = video.clientWidth;
  canvas.height = video.clientHeight;
  let defaultHeight = video.clientHeight;
  let canvasRatio = defaultHeight / video.clientHeight;
  ctx.fillStyle = 'red';
  var x, y;

  // ブラウザサイズ変更時
  let timer = '';
  window.onresize = function () {
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(function(){
      canvas.style.top = window.pageYOffset + video.getBoundingClientRect().top;
      canvas.style.width = video.clientWidth;
      canvas.style.height = video.clientHeight;
      sizeRatio = video.videoHeight / video.clientHeight;
      canvasRatio = defaultHeight / video.clientHeight;
    }, 100);
  };

  //next
  function proceedToNextFrame(){
    frameNum += 1;
    video.currentTime = Math.min(video.duration, video.currentTime+frameRate);
    console.log(frameNum);
    count = 0;
    showFrame.textContent = frameNum;
    showTime.textContent = Math.round(frameNum * frameRate * 1000);
  };

  nextFrame.addEventListener('click',function(){
      if (video.duration > (video.currentTime+frameRate) && count == 0){
        console.log('pushed next');
        proceedToNextFrame();
      };
  });

  //prev
  function backToPrevFrame(){
    frameNum -= 1;
    video.currentTime = Math.max(0, video.currentTime-frameRate);
    console.log(frameNum);
    count = 0;
    showFrame.textContent = frameNum;
    showTime.textContent = Math.round(frameNum * frameRate * 1000);
  };

  prevFrame.addEventListener('click',function(){
      if (frameNum > 0 && count == 0){
        console.log('pushed prev');
        backToPrevFrame();
      };
  });


  // 完了
  function finishFrame(){
    if (count == parts.length){
      // full_coordinateにall_coordinateを追加
      full_coordinate[frameNum] = all_coordinate;
      console.log(full_coordinate);
      all_coordinate = {};

      // フレーム進める
      if (video.duration > (video.currentTime+frameRate)){
        proceedToNextFrame();
      };

      // progressbar
      progressBar.style.width = Math.round(frameNum / maxFrame * 100)+'%';
      showProgression.textContent = Math.round(frameNum / maxFrame * 100)+'%';

      // 表示している座標を全削除
      ctx.clearRect(0, 0, video.videoWidth, video.videoHeight);
      while (showAll.firstChild){
        showAll.removeChild(showAll.firstChild);
      };

      //完了ボタンを押したときにいつでもPOSTできるようにjsonに変換しておく。
      let coordinates_json =JSON.stringify(full_coordinate);
      $(function(){
        $('#coordinate_form').val(coordinates_json);
        $('#fps_form').val(fps);

      }
      );

      // 最後のフレームで完了したら送信ボタンを押せるようにする
      if (frameNum == maxFrame){
        sendArr.disabled = false;
      };
      
      resetTable();
      deletePoints = [];

      
    };
  };

  document.getElementById('submit').addEventListener('click', function(event){
    finishFrame();
  });




  // クリックで座標取得時
  canvas.addEventListener('click', function(event){
    if (count < parts.length) {
      var positionX = event.pageX - window.pageXOffset - video.getBoundingClientRect().left;
      var positionY = event.pageY - window.pageYOffset - video.getBoundingClientRect().top;
      console.log(positionX+','+positionY);

      // pointSize = video.clientWidth / 100
      let canvasX = positionX * canvasRatio - pointSize / 2;
      let canvasY = positionY * canvasRatio - pointSize / 2;
      deletePoints[count] = [canvasX, canvasY];
      ctx.fillRect(canvasX, canvasY, pointSize, pointSize);
      console.log(canvasX+','+canvasY);

      x = Math.round(positionX*sizeRatio);
      y = originalHeight - Math.round(positionY*sizeRatio);
      part = parts[count];
      getPointToArray(part, x, y);

      showAll.rows[count].cells[2].innerHTML = x+', '+y;
      count += 1;
      console.log(count);

    };
  });



  function getPointToArray(part, x, y){
    all_coordinate[part] = [x, y];

    // let newRow = showAll.insertRow();

    // let newCell = newRow.insertCell();
    // let newText = document.createTextNode(count+1);
    // newCell.appendChild(newText);

    // newCell = newRow.insertCell();
    // newText = document.createTextNode(part);
    // newCell.appendChild(newText);


    // newCell = showAll.insertCell();
    // newText = document.createTextNode(x+', '+y);
    // newCell.appendChild(newText);
  };


  // 取消
  function deletePoint(){
    if (count > 0){
      count -= 1;
      ctx.clearRect(0, 0, video.videoWidth, video.videoHeight);
      deletePoints.pop();
      for (let point of deletePoints) {
        ctx.fillRect(point[0], point[1], pointSize, pointSize);
      };
      showAll.rows[count].cells[2].innerHTML = '';
    };
  };

  document.getElementById('delete').addEventListener('click', function(){
    deletePoint();
  });

  document.getElementById('send-arr').addEventListener('click',function(){
    formToCsv = document.getElementsByName('fromToCsv');
    formToCsv.submit();
  })

  window.document.onkeydown = function(event){
    console.log(event.key);
    //enterキーで完了
    if (event.key === 'Enter') {
      finishFrame();
    };

    // backspaceで取り消し
    if (event.key === 'Backspace') {
      deletePoint();
    };
  };



};
