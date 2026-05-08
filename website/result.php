<div class="grid md:grid-cols-3 gap-8">

<div class="result-card bg-white p-6 rounded-xl text-center shadow-lg">
<img src="../images/student1.jpg" class="w-24 h-24 mx-auto rounded-full mb-3">
<h3 class="font-bold">Rahul</h3>
<p class="text-[#E41C2A]">99.2%</p>
</div>

</div>
<style>

    .result-card {
opacity:0;
transform: translateY(80px) rotateX(30deg);
transition:1s;
}
.result-card.show {
opacity:1;
transform:translateY(0) rotateX(0);
}
</style>