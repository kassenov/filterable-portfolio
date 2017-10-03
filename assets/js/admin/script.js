(function ($) {
    "use strict";

    var _this,
        createBtnText,
        editBtnText,
        progressBtnText,
        saveBtnText,
        frame,
        images,
        selection;

    $('[data-modal="MediaFramePost"]').on('click', function (e) {
        e.preventDefault();

        _this = $(this);
        createBtnText = _this.data('create');
        editBtnText = _this.data('edit');
        progressBtnText = _this.data('progress');
        saveBtnText = _this.data('save');
        images = _this.data('value');
        selection = loadImages(images);

        var options = {
            title: createBtnText,
            state: 'gallery-edit',
            frame: 'post',
            selection: selection
        };

        if (frame || selection) {
            options['title'] = editBtnText;
        }

        frame = wp.media(options).open();

        // Tweak Views
        frame.menu.get('view').unset('cancel');
        frame.menu.get('view').unset('separateCancel');
        frame.menu.get('view').get('gallery-edit').el.innerHTML = editBtnText;
        frame.content.get('view').sidebar.unset('gallery'); // Hide Gallery Settings in sidebar

        // when editing a gallery
        overrideGalleryInsert();
        frame.on('toolbar:render:gallery-edit', function () {
            overrideGalleryInsert();
        });

        frame.on('content:render:browse', function (browser) {
            if (!browser) return;
            // Hide Gallery Settings in sidebar
            browser.sidebar.on('ready', function () {
                browser.sidebar.unset('gallery');
            });
            // Hide filter/search as they don't work
            browser.toolbar.on('ready', function () {
                if (browser.toolbar.controller._state === 'gallery-library') {
                    browser.toolbar.$el.hide();
                }
            });
        });

        // All images removed
        frame.state().get('library').on('remove', function () {
            var models = frame.state().get('library');
            if (models.length === 0) {
                selection = false;
                $.post(FilterablePortfolio.ajaxurl, {
                    ids: '',
                    action: 'fp_save_images',
                    post_id: FilterablePortfolio.post_id,
                    nonce: FilterablePortfolio.nonce
                });
            }
        });

        function overrideGalleryInsert() {
            frame.toolbar.get('view').set({
                insert: {
                    style: 'primary',
                    text: saveBtnText,
                    click: function () {
                        var models = frame.state().get('library'),
                            ids = '';

                        models.each(function (attachment) {
                            ids += attachment.id + ',';
                        });

                        this.el.innerHTML = progressBtnText;

                        $.ajax({
                            type: 'POST',
                            url: FilterablePortfolio.ajaxurl,
                            data: {
                                ids: ids,
                                action: 'fp_save_images',
                                post_id: FilterablePortfolio.post_id,
                                nonce: FilterablePortfolio.nonce
                            },
                            success: function () {
                                selection = loadImages(ids);
                                $('#fp_images_ids').val(ids);
                                frame.close();
                            },
                            dataType: 'html'
                        }).done(function (data) {
                            $('.fp_gallery_list').html(data);
                        });
                    }
                }
            });
        }

    });

    function loadImages(images) {
        if (images) {
            var shortcode = new wp.shortcode({
                tag: 'gallery',
                attrs: {ids: images},
                type: 'single'
            });

            var attachments = wp.media.gallery.attachments(shortcode);

            var selection = new wp.media.model.Selection(attachments.models, {
                props: attachments.props.toJSON(),
                multiple: true
            });

            selection.gallery = attachments.gallery;

            selection.more().done(function () {
                // Break ties with the query.
                selection.props.set({query: false});
                selection.unmirror();
                selection.props.unset('orderby');
            });

            return selection;
        }
        return false;
    }
})(jQuery);