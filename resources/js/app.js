import "./bootstrap";
import { Editor } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import FileHandler from "@tiptap/extension-file-handler";
import Image from "@tiptap/extension-image";

window.setupEditor = function (content) {
    let editor;

    return {
        content,

        initialize(element) {
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

                        onDrop: (currentEditor, files, pos) => {
                            files.forEach((file) => {
                                this.uploadImage(file, pos);
                            });
                        },

                        onPaste: (currentEditor, files, htmlContent) => {
                            if (htmlContent) return;

                            files.forEach((file) => {
                                this.uploadImage(file);
                            });
                        },
                    }),
                ],

                content: this.content,

                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML();
                },
            });

            this.$wire.on("editor-image-uploaded", ({ url, pos }) => {
                if (!editor) return;

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

            this.$watch("content", (value) => {
                if (value === editor.getHTML()) return;

                editor.commands.setContent(value, false);
            });
        },

        uploadImage(file, pos = null) {
            this.$wire.upload("image", file, () => {
                this.$wire.call("processImage", pos);
            });
        },
    };
};
