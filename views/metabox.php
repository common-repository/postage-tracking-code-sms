<div class="container">
	<?php if ( count( $data  ) ): ?>
		<?php foreach ( $data as $item ): ?>
            <div class="row">
                <div class="col mt-1 mb-1">
                    <p><?php echo nl2br( esc_html( $item ) ); ?></p>
                    <hr>
                </div>
            </div>
		<?php endforeach; ?>
	<?php else: ?>
        <div class="row">
            <div class="col">
                <p>داده ای برای نمایش وجود ندارد!</p>
            </div>
        </div>
	<?php endif; ?>
</div>