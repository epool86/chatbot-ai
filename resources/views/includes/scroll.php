<!DOCTYPE html>
<html>
<head>
<style>
.scroll-container {
    overflow: hidden;
    white-space: nowrap;
    width: 100%;
}

.scroll-text {
    display: inline-block;
    animation: scroll 20s linear infinite;
}

@keyframes scroll {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}
</style>
</head>
<body style="margin: 0; background: transparent;">
    <div class="scroll-container">
        <div class="scroll-text">Your scrolling text here - make it long enough to scroll smoothly</div>
    </div>
</body>
</html>