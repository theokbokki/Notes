import "./bootstrap";
import { Editor } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";

window.setupEditor = function (content) {
    let editor;

    return {
        content: content,

        init(element) {
            editor = new Editor({
                element: element,
                extensions: [StarterKit],
                content: this.content,
                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML();
                },
            });

            this.$watch("content", (content) => {
                if (content === editor.getHTML()) return;

                editor.commands.setContent(content, false);
            });
        },
    };
};
