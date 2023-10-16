
<article class="w-100">
  <form action="<?php echo BASE_URL.'/mission' ?>" class="bg-success-subtle rounded mt-4">
    <input type="text" hidden name="id" value="<?php echo $mission->getId() ?>">
    <button  type="submit" class="d-flex flex-column justify-content-start btn btn-outline-success w-100 h-100">
      <p class="fs-3 fw-semibold px-0 px-lg-3 pt-2"><?php echo  $mission->getTitle() ?></p>
      <p class="fs-5 px-0 px-lg-4"><?php echo  substr($mission->getDescription(), 0, 200).' ...' ?></p>
    </button>
  </form>
</article>