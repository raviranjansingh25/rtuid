<style>
.merit-cert-wrap { width: 100%; max-width: 520px; margin: 0 auto; }
.merit-cert-frame {
    position: relative;
    width: 100%;
    aspect-ratio: 724 / 1024;
    background-size: 100% 100%;
    background-repeat: no-repeat;
    background-position: center;
    box-shadow: 0 8px 24px rgba(0,0,0,.15);
}
.merit-cert-frame .mc-serial,
.merit-cert-frame .mc-name,
.merit-cert-frame .mc-course,
.merit-cert-frame .mc-place,
.merit-cert-frame .mc-from,
.merit-cert-frame .mc-on {
    position: absolute;
    font-family: Arial, Helvetica, sans-serif;
    color: #141414;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.merit-cert-frame .mc-serial {
    top: 5.8%;
    left: 78%;
    width: 18%;
    font-size: 1.4cqw;
}
.merit-cert-frame .mc-name {
    top: 37.5%;
    left: 12%;
    width: 76%;
    text-align: center;
    font-size: 2.2cqw;
}
.merit-cert-frame.tpl-2 .mc-name {
    top: 45.5%;
}
.merit-cert-frame .mc-course.tpl1 { top: 50.5%; left: 55%; width: 38%; font-size: 1.5cqw; }
.merit-cert-frame .mc-place.tpl1 { top: 57%; left: 52%; width: 40%; font-size: 1.5cqw; }
.merit-cert-frame .mc-from.tpl1 { top: 60%; left: 28%; width: 55%; font-size: 1.5cqw; }
.merit-cert-frame .mc-name { /* template 2 name slightly lower via parent aspect */ }
.merit-cert-frame .mc-course.tpl2 { top: 58.5%; left: 55%; width: 38%; font-size: 1.5cqw; }
.merit-cert-frame .mc-on.tpl2 { top: 62.2%; left: 28%; width: 55%; font-size: 1.5cqw; }
@container (min-width: 0) {}
.merit-cert-frame { container-type: inline-size; }
.merit-cert-frame .mc-serial { font-size: 2.8cqi; }
.merit-cert-frame .mc-name { font-size: 4cqi; }
.merit-cert-frame .mc-course,
.merit-cert-frame .mc-place,
.merit-cert-frame .mc-from,
.merit-cert-frame .mc-on { font-size: 2.6cqi; }
</style>
