          <?php if(isset($errors)): ?>
            <?php foreach($errors AS $error): ?>
              <div class="message bg-red-100 my-3"><?= $error ?></div>
            <?php endforeach; ?>
          <?php endif; ?>