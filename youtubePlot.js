let iframePlayer = document.getElementById('player');
let showAll = document.getElementById('show-all');
let showFrame = document.getElementById('show-frame');
let showMax = document.getElementById('show-max');
let showProgression = document.getElementById('show-progression');
let showTime = document.getElementById('show-time');
let sendArr = document.getElementById('send-arr');
let canvas = document.getElementById('canvas');
let ctx;
let progressBar = document.getElementById('progress-bar');
let table = document.getElementById('table');
let tableDisplay = document.getElementById('table-display');


let parts = JSON.parse(data);
let part;
let partCoordinates = {};
let allFramesCoordinates = [];
let deletePoints = [];
let originalPoints = [];
let originalHeight = 1080;
let sizeRatio;
let frameTime = 1/30;
let fps = 30;
let frameNum = 0;
var started = false;
let clickCount = 0;
let canvasRatio = 1;

let pointSize = 8;
let x, y;

maxFrame = Number(maxFrame);


//api用のJSを読み込む
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

//APIを実行
var player;
function onYouTubeIframeAPIReady() {
  player = new YT.Player("player",{
    events: {
        'onReady': onPlayerReady,//API呼び出しの受信を開始する準備ができると起動
        'onStateChange': onPlayerStateChange// プレーヤーの状態が変わると起動
    }
  });
}


function onPlayerReady(event) {
  player.loadVideoById(youtubeID, startTime-1, "large");
  player.playVideo();
  setTimeout(function(){player.pauseVideo()},1000);
  canvas.style.left = iframePlayer.getBoundingClientRect().left;
  canvas.style.top = iframePlayer.getBoundingClientRect().top;
  canvas.width = iframePlayer.clientWidth;
  canvas.height = iframePlayer.clientHeight;
}

var done = false;
function onPlayerStateChange(event) {console.log(event.data);
  if (event.data == YT.PlayerState.PLAYING && !done) {
    done = true;
  };

  higestQuality = player.getAvailableQualityLevels()[0];
  console.log(higestQuality);
  switch (higestQuality) {
    case 'highres':
      originalHeight = 1080;
      break;
    case 'hd4320':
      originalHeight = 4320;
      break;
    case 'hd2160':
      originalHeight = 2160;
      break;
    case 'hd1440':
      originalHeight = 1440;
      break;
    case 'hd1080':
      originalHeight = 1080;
      break;
    case 'hd720':
      originalHeight = 720;
      break;
    case 'large':
      originalHeight = 480;
      break;
    case 'medium':
      originalHeight = 360;
      break;
    case 'small':
      originalHeight = 240;
      break;

  };
  console.log(originalHeight);
  sizeRatio = originalHeight / iframePlayer.clientHeight;
}


showMax.textContent = maxFrame;
showFrame.textContent = frameNum;
showProgression.textContent = Math.round(frameNum / maxFrame * 100);
showTime.textContent = Math.round(frameNum * frameTime * 1000);


sizeRatio = originalHeight / iframePlayer.clientHeight;
ctx = canvas.getContext('2d');
canvas.style.left = iframePlayer.getBoundingClientRect().left;
canvas.style.top = iframePlayer.getBoundingClientRect().top;
canvas.width = iframePlayer.clientWidth;
canvas.height = iframePlayer.clientHeight;
let defaultHeight = iframePlayer.clientHeight;


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

// ブラウザサイズ変更時
let timer = '';
window.onresize = function () {
  if (timer) {
    clearTimeout(timer);
  }
  timer = setTimeout(function(){
      let iframePlayer = document.getElementById('player');
      canvas.style.top = window.pageYOffset + iframePlayer.getBoundingClientRect().top;
      canvas.style.width = iframePlayer.clientWidth;
      canvas.style.height = iframePlayer.clientHeight;
      canvasRatio = defaultHeight / iframePlayer.clientHeight;
      sizeRatio = originalHeight / iframePlayer.clientHeight;
  }, 1000);
};


// 開始
document.getElementById('start').addEventListener('click', function(event){
  player.pauseVideo();
  player.seekTo(startTime, true);
  canvas.style.zIndex = 100;
  table.style.zIndex = 90;
  frameNum = 0;
  showFrame.textContent = 0;
  showTime.textContent = 0;
  started = true;

  canvas.style.top = window.pageYOffset + iframePlayer.getBoundingClientRect().top;
  canvas.style.width = iframePlayer.clientWidth;
  canvas.style.height = iframePlayer.clientHeight;
  canvasRatio = defaultHeight / iframePlayer.clientHeight;
  sizeRatio = originalHeight / iframePlayer.clientHeight;
});

let tableDisplayed = true;
tableDisplay.addEventListener('click', function(){
  if(tableDisplayed == true) {
    table.style.zIndex = -10;
    tableDisplayed = false;
  }else {
    table.style.zIndex = 90;
    tableDisplayed = true;
  }; 
})



document.getElementById('prev-frame').addEventListener('click', function(event){
  if (frameNum > 0 && clickCount == 0){
    frameNum -= 1;
    player.seekTo(player.getCurrentTime()-frameTime, true);
    clickCount = 0;
    showFrame.textContent = frameNum;
    showTime.textContent = Math.round(frameNum * frameTime * 1000);
  } else if (clickCount > 0) {
    window.alert("全ての座標を削除してください。");
  };
});

function proceedToNextFrame(){
  frameNum += 1;
  console.log(frameNum);
  player.seekTo(player.getCurrentTime()+frameTime, true);
  clickCount = 0;
  showFrame.textContent = frameNum;
  showTime.textContent = Math.round(frameNum * frameTime * 1000);
};

document.getElementById('next-frame').addEventListener('click', function(event){
  if (frameNum < maxFrame && clickCount == 0) {
    proceedToNextFrame();
  } else if (clickCount > 0) {
    window.alert("全ての座標を削除してください。");
  };
});


// クリックで座標取得時

// 線描画
function drawLine(startX, startY, endX, endY){
  ctx.beginPath();
  ctx.moveTo(startX+pointSize/2, startY+pointSize/2);
  ctx.lineTo(endX+pointSize/2, endY+pointSize/2);

  ctx.strokeStyle = "lime";
  ctx.lineWidth = 1;

  ctx.stroke();
}

function drawPose(count, points, cx, cy){
  var start, startX, startY;
    if (count <= 1){
      color = 'red'
      if (count == 1){
      start = points[count-1];
      console.log(start);
      startX = start[0];
      startY = start[1];
      };
    } else if (count > 1 && count <= 4){
      color = 'orange'
      start = points[count-1];
      startX = start[0];
      startY = start[1];
    } else if (count > 4 && count <= 7){
      if (count == 5){
        start = points[1];
      } else start = points[count-1];
      startX = start[0];
      startY = start[1];
      color = 'yellow'
    } else if (count > 7 && count <= 10){
      if (count == 8){
        start = points[1];
      } else start = points[count-1];
      startX = start[0];
      startY = start[1];
      color = 'lime'
    } else if (count > 10 && count <= 13){
      if (count == 11){
        start = points[1];
      } else start = points[count-1];
      startX = start[0];
      startY = start[1];
      color = 'aqua';
    } else if (count == 14) color = 'fuchsia';
    // var color = 'rgb('+r+','+g+','+b+')';
    drawLine(startX, startY, cx, cy);
    ctx.fillStyle = color;
    ctx.fillRect(cx, cy, pointSize, pointSize);
};

function drawPreviousPose(count, points, cx, cy){
  var start, startX, startY;
  if (count <= 1){
    if (count == 1){
    start = points[count-1];
    console.log(start);
    startX = start[0];
    startY = start[1];
    };
  } else if (count > 1 && count <= 4){
    start = points[count-1];
    startX = start[0];
    startY = start[1];
  } else if (count > 4 && count <= 7){
    if (count == 5){
      start = points[1];
    } else start = points[count-1];
    startX = start[0];
    startY = start[1];
  } else if (count > 7 && count <= 10){
    if (count == 8){
      start = points[1];
    } else start = points[count-1];
    startX = start[0];
    startY = start[1];
  } else if (count > 10 && count <= 13){
    if (count == 11){
      start = points[1];
    } else start = points[count-1];
    startX = start[0];
    startY = start[1];
  }

  ctx.beginPath();
  ctx.moveTo(startX+pointSize/2, startY+pointSize/2);
  ctx.lineTo(cx+pointSize/2, cy+pointSize/2);

  ctx.strokeStyle = "gray";
  ctx.lineWidth = 1;

  ctx.stroke();

  ctx.fillStyle = "gray";
  ctx.fillRect(cx, cy, pointSize, pointSize);
}

canvas.addEventListener('click', function(event){
  console.log('clicked')
  if (clickCount < parts.length && started) {
    var positionX = event.pageX - window.pageXOffset - iframePlayer.getBoundingClientRect().left;
    var positionY = event.pageY - window.pageYOffset - iframePlayer.getBoundingClientRect().top;
    console.log(positionX+','+positionY);

    // pointSize = iframePlayer.clientWidth / 100;
    let canvasX = positionX * canvasRatio - pointSize / 2;
    let canvasY = positionY * canvasRatio - pointSize / 2;
    deletePoints[clickCount] = [canvasX, canvasY];
    

    drawPose(clickCount, deletePoints, canvasX, canvasY);

    x = Math.round(positionX*sizeRatio);
    y = originalHeight - Math.round(positionY*sizeRatio);
    part = parts[clickCount];
    getPointToArray(part, x, y);
    showAll.rows[clickCount].cells[2].innerHTML = x+', '+y;
    clickCount += 1;
    console.log(clickCount);
  };
});

function getPointToArray(part, x, y){
  partCoordinates[part] = [x, y];
};


// 取消
function deletePoint(){
  if (clickCount > 0){
    // 表示している座標を1つ削除
    clickCount -= 1;
    ctx.clearRect(0, 0, canvas.clientWidth, canvas.clientHeight);
    deletePoints.pop();
    for (let i = 0; i < deletePoints.length; i++) {
      let point = deletePoints[i];
      drawPose(i, deletePoints, point[0], point[1]);
    }
    showAll.rows[clickCount].cells[2].innerHTML = '';

    if (frameNum > 0) {
      let previousPose = originalPoints[frameNum-1];
      for (let i = 0; i < previousPose.length; i++) {
        let point = previousPose[i];
        drawPreviousPose(i, previousPose, point[0], point[1]);
      }
    }
  };
}


// 完了
function finishFrame(){
  if (clickCount == parts.length){
    allFramesCoordinates[frameNum] = partCoordinates;
    console.log(allFramesCoordinates);
    partCoordinates = {};
    originalPoints[frameNum] = deletePoints;

    if (frameNum < maxFrame) {
      proceedToNextFrame();
    } else if (frameNum == maxFrame) {
      // sendArr.disabled = false;
      frameNum += 1;
      window.alert("全ての入力が完了しました。送信ボタンを押してください。");
    };

    // progressbar
    var per = frameNum / (maxFrame + 1);
    console.log(maxFrame+1);
    progressBar.style.width = Math.round(per*100)+'%';
    showProgression.textContent = Math.round(per*100)+'%';

    // 表示している座標を全削除
    ctx.clearRect(0, 0, iframePlayer.clientWidth, iframePlayer.clientHeight);
    while (showAll.firstChild){
      showAll.removeChild(showAll.firstChild);
    };

    // 前フレームの座標をグレーで表示
    for (let i = 0; i < deletePoints.length; i++) {
      let point = deletePoints[i];
      drawPreviousPose(i, deletePoints, point[0], point[1]);
    }
    // for (let i = 0; i < deletePoints.length; i++) {
    //   let point = deletePoints[i];
    //   drawPose(i, deletePoints, point[0], point[1]);
    // }

    // 完了ボタンを押したときにいつでもPOSTできるようにjsonに変換しておく。
    let coordinates_json =JSON.stringify(allFramesCoordinates);
    console.log(coordinates_json);
    $(function(){
      $('#coordinate_form').val(coordinates_json);
      $('#fps_form').val(fps);
    }
    );

    resetTable();
    deletePoints = [];
  } else if (clickCount < parts.length) {
    window.alert("全部位の座標を入力してください。")
  };
};

document.getElementById('submit').addEventListener('click', function(event){
  finishFrame();
});

document.getElementById('delete').addEventListener('click', function(event){
  deletePoint();
});

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



