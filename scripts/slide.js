const slides = document.querySelectorAll('.slides')
const prevBtn = document.getElementById('prev-btn')
const nextBtn = document.getElementById('next-btn')
const dots = document.querySelectorAll('.dot')

if (prevBtn != null) {
    let index = 0
    const autoPlaySlide = () =>{
        removeDotsOpacity()
        if(index===slides.length-1) index= -1
        index++
        dots[index].style.opacity='1'
        moveSlide()
    }

    let timer = setInterval(autoPlaySlide,6000)

    dots[0].style.opacity='1'
    slides.forEach((slide,index)=>{
        slide.style.left=`${index*100}%`
    })

    const moveSlide = () =>{
        slides.forEach((slide)=>{
            slide.style.transform=`translateX(-${index*100}%)`
        })
    }

    const removeDotsOpacity = () =>{
        dots.forEach((dot)=>{
            dot.style.opacity='.2'
        })
    }

    dots.forEach((dot,i)=>{
        dot.addEventListener("click",(e)=>{
            index=i
            removeDotsOpacity()
            e.target.style.opacity='1'
            moveSlide()
            clearInterval(timer)
            timer = setInterval(autoPlaySlide,6000)
        })
    })

    prevBtn.addEventListener('click',()=>{
        if(index===0) index=slides.length
        index--
        removeDotsOpacity()
        dots[index].style.opacity='1'
        moveSlide()
        clearInterval(timer)
        timer = setInterval(autoPlaySlide,6000)
    })

    nextBtn.addEventListener('click',()=>{
        if(index===slides.length-1) index= -1
        index++
        removeDotsOpacity()
        dots[index].style.opacity='1'
        moveSlide()
        clearInterval(timer)
        timer = setInterval(autoPlaySlide,6000)
    })
}