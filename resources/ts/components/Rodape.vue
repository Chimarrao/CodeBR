<template>
    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                Code BR - Um blog sobre programação &copy; {{ currentYear }}. Propriedade intelectual não existe.
            </p>
            <p>
                <a href="https://www.linkedin.com/in/lucasreolon/" class="m-1 redesocial" target="_blank"
                    rel="noopener noreferrer">
                    <i class="fab fa-linkedin fa-2x"></i>
                </a>
                <a href="https://github.com/Chimarrao/CodeBR/" class="m-1 redesocial" target="_blank"
                    rel="noopener noreferrer">
                    <i class="fab fa-github fa-2x"></i>
                </a>
            </p>
        </div>
    </footer>
</template>

<script>
export default {
    name: "Rodape",
    data() {
        return {
            currentYear: new Date().getFullYear(),
        };
    },
};
</script>