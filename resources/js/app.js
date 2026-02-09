import "./bootstrap";
import { Editor } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import FileHandler from "@tiptap/extension-file-handler";
import Image from "@tiptap/extension-image";

window.setupEditor = function (content, element) {
    let editor;
    let saveTimeout = null;

    return {
        content,

        init() {
            editor = new Editor({
                element,
                extensions: [
                    StarterKit,
                    Image,
                    FileHandler.configure({
                        allowedMimeTypes: [
                            "image/png",
                            "image/jpeg",
                            "image/gif",
                            "image/webp",
                        ],

                        onDrop: (editor, files, pos) => {
                            files.forEach(file => {
                                this.uploadImage(file, pos);
                            });
                        },

                        onPaste: (editor, files, html) => {
                            if (html) return;

                            files.forEach(file => {
                                this.uploadImage(file);
                            });
                        },
                    }),
                ],

                content: this.content,

                onUpdate: ({ editor }) => {
                    const html = editor.getHTML();

                    clearTimeout(saveTimeout);

                    saveTimeout = setTimeout(() => {
                        this.content = html;
                        this.$wire.saveContent(html);
                    }, 500);
                },
            });

            this.$wire.on("editor-image-uploaded", ({ url, pos }) => {
                const insertPos = pos ?? editor.state.selection.anchor;

                editor
                    .chain()
                    .insertContentAt(insertPos, {
                        type: "image",
                        attrs: { src: url },
                    })
                    .focus()
                    .run();
            });
        },

        uploadImage(file, pos = null) {
            this.$wire.upload("image", file, () => {
                this.$wire.call("processImage", pos);
            });
        },
    };
};
