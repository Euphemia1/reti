import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { createPageUrl } from "@/utils";
import { motion, AnimatePresence } from "framer-motion";
import { ArrowRight, Play, ChevronLeft, ChevronRight } from "lucide-react";

const slides = [
  {
    image: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=1920&q=80",
    title: "Get Your",
    highlight: "Education",
    subtitle: "Today!",
    description: "TEVETA accredited vocational training for a brighter future"
  },
  {
    image: "https://images.unsplash.com/photo-1504917595217-d4dc5ebb6122?w=1920&q=80",
    title: "Build Skills",
    highlight: "That Matter",
    subtitle: "",
    description: "Practical hands-on training in construction and engineering"
  },
  {
    image: "https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=1920&q=80",
    title: "Your Future",
    highlight: "Starts Here",
    subtitle: "",
    description: "Join thousands of successful graduates across Zambia"
  }
];

export default function HeroSection() {
  const [currentSlide, setCurrentSlide] = useState(0);

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, 6000);
    return () => clearInterval(timer);
  }, []);

  const nextSlide = () => setCurrentSlide((prev) => (prev + 1) % slides.length);
  const prevSlide = () => setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);

  return (
    <section className="relative h-[90vh] min-h-[600px] overflow-hidden">
      {/* Background Slides */}
      <AnimatePresence mode="wait">
        <motion.div
          key={currentSlide}
          initial={{ opacity: 0, scale: 1.1 }}
          animate={{ opacity: 1, scale: 1 }}
          exit={{ opacity: 0 }}
          transition={{ duration: 0.8 }}
          className="absolute inset-0"
        >
          <div 
            className="absolute inset-0 bg-cover bg-center"
            style={{ backgroundImage: `url(${slides[currentSlide].image})` }}
          />
          <div className="absolute inset-0 bg-gradient-to-r from-[#1e3a5f]/90 via-[#1e3a5f]/70 to-transparent" />
        </motion.div>
      </AnimatePresence>

      {/* Content */}
      <div className="relative z-10 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
        <div className="max-w-2xl">
          <AnimatePresence mode="wait">
            <motion.div
              key={currentSlide}
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -30 }}
              transition={{ duration: 0.5, delay: 0.2 }}
            >
              <motion.div 
                initial={{ width: 0 }}
                animate={{ width: "80px" }}
                transition={{ duration: 0.5 }}
                className="h-1 bg-[#f5a623] mb-8"
              />
              
              <h1 className="text-5xl md:text-7xl font-bold text-white leading-tight mb-6">
                {slides[currentSlide].title}{" "}
                <span className="text-[#f5a623] bg-[#f5a623]/20 px-3 py-1 rounded-lg">
                  {slides[currentSlide].highlight}
                </span>
                {slides[currentSlide].subtitle && (
                  <span className="block mt-2">{slides[currentSlide].subtitle}</span>
                )}
              </h1>
              
              <p className="text-xl text-gray-200 mb-10 leading-relaxed">
                {slides[currentSlide].description}
              </p>

              <div className="flex flex-wrap gap-4">
                <Link
                  to={createPageUrl("Courses")}
                  className="group bg-[#f5a623] hover:bg-[#e09515] text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-lg shadow-[#f5a623]/30 hover:shadow-xl hover:shadow-[#f5a623]/40 flex items-center gap-2"
                >
                  Explore Courses
                  <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                </Link>
                <Link
                  to={createPageUrl("About")}
                  className="group bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 border border-white/30 flex items-center gap-2"
                >
                  <Play className="w-5 h-5" />
                  Learn More
                </Link>
              </div>
            </motion.div>
          </AnimatePresence>
        </div>
      </div>

      {/* Slide Navigation */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-4">
        <button
          onClick={prevSlide}
          className="w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white hover:bg-white/20 transition-colors"
        >
          <ChevronLeft className="w-6 h-6" />
        </button>
        
        <div className="flex gap-2">
          {slides.map((_, index) => (
            <button
              key={index}
              onClick={() => setCurrentSlide(index)}
              className={`h-2 rounded-full transition-all duration-300 ${
                index === currentSlide 
                  ? "w-8 bg-[#f5a623]" 
                  : "w-2 bg-white/50 hover:bg-white/70"
              }`}
            />
          ))}
        </div>
        
        <button
          onClick={nextSlide}
          className="w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white hover:bg-white/20 transition-colors"
        >
          <ChevronRight className="w-6 h-6" />
        </button>
      </div>

      {/* Decorative Elements */}
      <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white to-transparent" />
    </section>
  );
}