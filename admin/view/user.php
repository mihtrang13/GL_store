  <!-- Phần chính -->

  <div class="container_table">
      <div class="addnew">
          <a class="addspnew" href="#">Thêm Thành viên</a>
      </div>
      <table class="table_form">
          <tr>
              <th class="thead">#</th>
              <th class="thead">Tên đăng nhập</th>
              <th class="thead">Họ và tên</th>
              <th class="thead">Mật Khẩu</th>
              <th class="thead">Email</th>
              <th class="thead">Thao Tác</th>
              <th class="thead">Trạng thái</th>
              <th class="thead">Thay đổi trạng thái</th>
          </tr>

          <?php
            if (isset($users)) :
                foreach ($users as $user) :
                    $status_text = ($user['status'] == 0) ? "đang hoạt động" : "đã bị khóa";
            ?>
                  <tr>
                      <td class="tbody"><?= $user['id'] ?></td>
                      <td class="tbody"><?= $user['username'] ?></td>
                      <td class="tbody"><?= $user['full_name'] ?></td>
                      <td class="tbody"><?= $user['password'] ?></td>
                      <td class="tbody"><?= $user['email'] ?></td>
                      <td class="tbody"> <a href="index.php?act=updateUser&id=<?= $user['id'] ?>">Sửa</a></td>
                      <td class="tbody"><?= $status_text ?></td>
                      <td class="tbody">
                          <form class="custom-form" action="" method="post" enctype="multipart/form-data">
                              <select class="userSelector" name="status_<?= $user['id'] ?>">
                                  <option value="0" <?= ($user['status'] == 0) ? 'selected' : '' ?>>Mở</option>
                                  <option value="1" <?= ($user['status'] == 1) ? 'selected' : '' ?>>Khóa</option>
                              </select>
                              <input type="hidden" name="selectedUserId" value="<?= $user['id'] ?>">
                              <input type="submit" name="thaydoi" value="Thay đổi trạng thái">
                          </form>

                      </td>
                  </tr>
          <?php

                endforeach;
            endif;

            ?>

      </table>
      <div class="clearfix"></div>
  </div>
  <form action="index.php?act=addUser" class="forms" method="post">
      <h1 class="title">Nhập Thành Viên</h1>
      <label for="">Tên đăng nhập</label>
      <input type="text" name="username" placeholder="Nhập tên đăng nhập.." class="input"><br>
      <label for="">Mật khẩu</label>
      <input type="password" name="password" placeholder="Nhập mật khẩu.." class="input"><br>
      <label for="">Họ tên</label>
      <input type="text" name="full_name" placeholder="Nhập họ tên.." class="input"><br>
      <label for="">Số điện thoại</label>
      <input type="text" name="phone" placeholder="Nhập số điện thoại.." class="input"><br>
      <label for="">Email</label>
      <input type="email" name="email" placeholder="Nhập email.." class="input"><br>
      <button class="button" name="addUser">Thêm mới</button>
      <span class="close">X</span>
  </form>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="js/main.js"></script>

  <script>
      $(".nav").click(function() {
          $("#mySidenav").css('width', '70px');
          $("#main").css('margin-left', '70px');
          $(".logo").css('visibility', 'hidden');
          $(".logo span").css('visibility', 'visible');
          $(".logo span").css('margin-left', '-10px');
          $(".icon-a").css('visibility', 'hidden');
          $(".icons").css('visibility', 'visible');
          $(".icons").css('margin-left', '-8px');
          $(".nav").css('display', 'none');
          $(".nav2").css('display', 'block');
      });

      $(".nav2").click(function() {
          $("#mySidenav").css('width', '300px');
          $("#main").css('margin-left', '300px');
          $(".logo").css('visibility', 'visible');
          $(".icon-a").css('visibility', 'visible');
          $(".icons").css('visibility', 'visible');
          $(".nav").css('display', 'block');
          $(".nav2").css('display', 'none');
      });
  </script>

  </body>

  </body>

  </html>