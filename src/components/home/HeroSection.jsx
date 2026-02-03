import React, { useState, useEffect, useRef } from "react";
import { Link } from "react-router-dom";
import { createPageUrl } from "@/utils";
import { motion, AnimatePresence, useScroll, useTransform, useSpring } from "framer-motion";
import { ArrowRight, Play, ChevronLeft, ChevronRight, Sparkles, Zap, Award, TrendingUp, Globe, Users, BookOpen, Star } from "lucide-react";

const slides = [
  {
    image: "/src/photos/20250617_113144.jpg",
    title: "Transform Your",
    highlight: "Future",
    subtitle: "Today!",
    description: "Join Africa's premier innovation hub where cutting-edge technology meets world-class education",
    stats: { students: "15,000+", courses: "200+", success: "98%" }
  },
  {
    image: "/src/photos/20250617_102200.jpg",
    title: "Innovate",
    highlight: "Create",
    subtitle: "Lead",
    description: "Experience the future of learning with AI-powered education and global industry partnerships",
    stats: { students: "50+", countries: "120", partners: "500+" }
  },
  {
    image: "/src/photos/20250617_153708.jpg",
    title: "Global",
    highlight: "Excellence",
    subtitle: "Local Impact",
    description: "Be part of a revolutionary educational ecosystem shaping tomorrow's leaders today",
    stats: { students: "1000+", research: "50", awards: "25" }
  }
];

// Floating bubbles component
const FloatingBubbles = () => {
  return (
    <div className="absolute inset-0 overflow-hidden">
      {[...Array(20)].map((_, i) => (
        <motion.div
          key={i}
          className="absolute bg-gradient-to-r from-blue-400/20 to-purple-400/20 rounded-full blur-xl"
          style={{
            width: Math.random() * 100 + 50 + 'px',
            height: Math.random() * 100 + 50 + 'px',
            left: Math.random() * 100 + '%',
            top: Math.random() * 100 + '%',
          }}
          animate={{
            x: [0, Math.random() * 200 - 100],
            y: [0, Math.random() * 200 - 100],
            scale: [1, 1.2, 1],
            opacity: [0.3, 0.6, 0.3],
          }}
          transition={{
            duration: Math.random() * 10 + 10,
            repeat: Infinity,
            repeatType: "reverse",
            ease: "easeInOut"
          }}
        />
      ))}
    </div>
  );
};

// Interactive particles
const InteractiveParticles = () => {
  const [mousePosition, setMousePosition] = useState({ x: 0, y: 0 });
  
  useEffect(() => {
    const handleMouseMove = (e: MouseEvent) => {
      setMousePosition({ x: e.clientX, y: e.clientY });
    };
    window.addEventListener('mousemove', handleMouseMove);
    return () => window.removeEventListener('mousemove', handleMouseMove);
  }, []);
  
  return (
    <div className="absolute inset-0 pointer-events-none">
      {[...Array(30)].map((_, i) => (
        <motion.div
          key={i}
          className="absolute w-1 h-1 bg-yellow-400 rounded-full"
          style={{
            left: Math.random() * 100 + '%',
            top: Math.random() * 100 + '%',
          }}
          animate={{
            x: mousePosition.x * 0.01,
            y: mousePosition.y * 0.01,
            scale: [1, 2, 1],
            opacity: [0.5, 1, 0.5],
          }}
          transition={{
            duration: 2,
            repeat: Infinity,
            delay: i * 0.1,
          }}
        />
      ))}
    </div>
  );
};

export default function HeroSection() {
  const [currentSlide, setCurrentSlide] = useState(0);
  const [isHovered, setIsHovered] = useState(false);
  const containerRef = useRef(null);
  const { scrollYProgress } = useScroll({ container: containerRef });
  const scale = useTransform(scrollYProgress, [0, 1], [1, 1.1]);
  const rotateX = useTransform(scrollYProgress, [0, 1], [0, 5]);
  
  const springConfig = { damping: 20, stiffness: 300 };
  const scaleSpring = useSpring(scale, springConfig);

  useEffect(() => {
    if (!isHovered) {
      const timer = setInterval(() => {
        setCurrentSlide((prev) => (prev + 1) % slides.length);
      }, 5000);
      return () => clearInterval(timer);
    }
  }, [isHovered]);

  const nextSlide = () => setCurrentSlide((prev) => (prev + 1) % slides.length);
  const prevSlide = () => setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);

  return (
    <section 
      ref={containerRef}
      className="relative h-[100vh] min-h-[700px] overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-purple-900"
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
    >
      {/* Animated Background Elements */}
      <FloatingBubbles />
      <InteractiveParticles />
      
      {/* Grid Pattern Overlay */}
      <div className="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30" />
      
      {/* Background Slides */}
      <AnimatePresence mode="wait">
        <motion.div
          key={currentSlide}
          initial={{ opacity: 0, scale: 1.2, rotateX: 10 }}
          animate={{ opacity: 0.3, scale: 1, rotateX: 0 }}
          exit={{ opacity: 0, scale: 0.8, rotateX: -10 }}
          transition={{ duration: 1.2, ease: "easeInOut" }}
          className="absolute inset-0"
          style={{ scale: scaleSpring, rotateX }}
        >
          <div 
            className="absolute inset-0 bg-cover bg-center"
            style={{ backgroundImage: `url(${slides[currentSlide].image})` }}
          />
          <div className="absolute inset-0 bg-gradient-to-r from-blue-900/80 via-purple-900/60 to-transparent" />
        </motion.div>
      </AnimatePresence>
      
      {/* Floating Elements */}
      <motion.div
        animate={{
          y: [0, -20, 0],
          rotate: [0, 5, -5, 0],
        }}
        transition={{
          duration: 6,
          repeat: Infinity,
          ease: "easeInOut"
        }}
        className="absolute top-20 right-20 text-yellow-400"
      >
        <Star className="w-16 h-16" fill="currentColor" />
      </motion.div>
      
      <motion.div
        animate={{
          x: [0, 30, 0],
          y: [0, -30, 0],
        }}
        transition={{
          duration: 8,
          repeat: Infinity,
          ease: "easeInOut"
        }}
        className="absolute bottom-20 left-20 text-blue-400"
      >
        <Zap className="w-12 h-12" fill="currentColor" />
      </motion.div>

      {/* Content */}
      <div className="relative z-10 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
        <div className="max-w-3xl">
          <AnimatePresence mode="wait">
            <motion.div
              key={currentSlide}
              initial={{ opacity: 0, y: 50, x: -50 }}
              animate={{ opacity: 1, y: 0, x: 0 }}
              exit={{ opacity: 0, y: -50, x: 50 }}
              transition={{ duration: 0.8, delay: 0.2 }}
            >
              {/* Badge */}
              <motion.div 
                initial={{ width: 0, opacity: 0 }}
                animate={{ width: "120px", opacity: 1 }}
                transition={{ duration: 0.8, delay: 0.4 }}
                className="h-1 bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 mb-8 rounded-full shadow-lg shadow-orange-400/50"
              />
              
              <motion.div
                initial={{ opacity: 0, scale: 0.8 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ duration: 0.6, delay: 0.5 }}
                className="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600/20 to-blue-600/20 backdrop-blur-md border border-purple-400/30 rounded-full px-6 py-3 mb-8"
              >
                <Sparkles className="w-5 h-5 text-yellow-400" />
                <span className="text-purple-200 font-semibold text-sm tracking-wider uppercase">
                  Innovation Hub 2025
                </span>
                <Globe className="w-5 h-5 text-blue-400 animate-spin-slow" />
              </motion.div>
              
              {/* Title */}
              <h1 className="text-6xl md:text-8xl font-bold text-white leading-tight mb-6">
                <motion.span
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.6, delay: 0.6 }}
                  className="block"
                >
                  {slides[currentSlide].title}{" "}
                </motion.span>
                <motion.span 
                  initial={{ opacity: 0, scale: 0.5 }}
                  animate={{ opacity: 1, scale: 1 }}
                  transition={{ duration: 0.6, delay: 0.7 }}
                  className="inline-block bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 bg-clip-text text-transparent px-4 py-2 rounded-2xl shadow-2xl shadow-orange-400/30"
                >
                  {slides[currentSlide].highlight}
                </motion.span>
                {slides[currentSlide].subtitle && (
                  <motion.span
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ duration: 0.6, delay: 0.8 }}
                    className="block text-4xl md:text-5xl text-yellow-400 mt-2"
                  >
                    {slides[currentSlide].subtitle}
                  </motion.span>
                )}
              </h1>
              
              {/* Description */}
              <motion.p 
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: 0.9 }}
                className="text-xl md:text-2xl text-gray-200 mb-10 leading-relaxed"
              >
                {slides[currentSlide].description}
              </motion.p>
              
              {/* Stats */}
              <motion.div
                initial={{ opacity: 0, y: 30 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: 1.0 }}
                className="grid grid-cols-3 gap-6 mb-12"
              >
                {Object.entries(slides[currentSlide].stats).map(([key, value], index) => (
                  <motion.div
                    key={key}
                    initial={{ opacity: 0, scale: 0.8 }}
                    animate={{ opacity: 1, scale: 1 }}
                    transition={{ duration: 0.4, delay: 1.1 + index * 0.1 }}
                    className="text-center"
                  >
                    <div className="text-3xl font-bold text-yellow-400 mb-1">{value}</div>
                    <div className="text-sm text-gray-300 capitalize">{key}</div>
                  </motion.div>
                ))}
              </motion.div>
              
              {/* Buttons */}
              <motion.div
                initial={{ opacity: 0, y: 30 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: 1.2 }}
                className="flex flex-wrap gap-6"
              >
                <Link
                  to={createPageUrl("Courses")}
                  className="group relative overflow-hidden bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 hover:from-yellow-500 hover:via-orange-500 hover:to-red-500 text-white px-10 py-5 rounded-2xl font-bold text-lg transition-all duration-500 shadow-2xl shadow-orange-400/30 hover:shadow-orange-400/50 flex items-center gap-3 transform hover:scale-105"
                >
                  <span className="relative z-10 flex items-center gap-3">
                    <Zap className="w-6 h-6" />
                    Explore Programs
                    <ArrowRight className="w-5 h-5 group-hover:translate-x-2 transition-transform" />
                  </span>
                  <motion.div
                    className="absolute inset-0 bg-white opacity-0 group-hover:opacity-20"
                    initial={{ x: "-100%" }}
                    whileHover={{ x: "100%" }}
                    transition={{ duration: 0.5 }}
                  />
                </Link>
                <Link
                  to={createPageUrl("About")}
                  className="group relative overflow-hidden bg-white/10 backdrop-blur-md hover:bg-white/20 text-white px-10 py-5 rounded-2xl font-bold text-lg transition-all duration-500 border-2 border-white/30 hover:border-white/50 flex items-center gap-3 transform hover:scale-105"
                >
                  <span className="relative z-10 flex items-center gap-3">
                    <Play className="w-6 h-6" />
                    Discover More
                    <Award className="w-5 h-5 group-hover:rotate-12 transition-transform" />
                  </span>
                  <motion.div
                    className="absolute inset-0 bg-gradient-to-r from-purple-600/20 to-blue-600/20 opacity-0 group-hover:opacity-100"
                    initial={{ scale: 0 }}
                    whileHover={{ scale: 1 }}
                    transition={{ duration: 0.3 }}
                  />
                </Link>
              </motion.div>
            </motion.div>
          </AnimatePresence>
        </div>
      </div>

      {/* Enhanced Slide Navigation */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-6">
        <motion.button
          whileHover={{ scale: 1.1 }}
          whileTap={{ scale: 0.9 }}
          onClick={prevSlide}
          className="w-14 h-14 rounded-full bg-white/10 backdrop-blur-md border-2 border-white/30 flex items-center justify-center text-white hover:bg-white/20 hover:border-white/50 transition-all duration-300 shadow-lg"
        >
          <ChevronLeft className="w-7 h-7" />
        </motion.button>
        
        <div className="flex gap-3">
          {slides.map((_, index) => (
            <motion.button
              key={index}
              onClick={() => setCurrentSlide(index)}
              className={`h-3 rounded-full transition-all duration-500 ${
                index === currentSlide 
                  ? "w-12 bg-gradient-to-r from-yellow-400 to-orange-400 shadow-lg shadow-orange-400/50" 
                  : "w-3 bg-white/30 hover:bg-white/50 hover:w-6"
              }`}
              whileHover={{ scale: 1.2 }}
              whileTap={{ scale: 0.8 }}
            />
          ))}
        </div>
        
        <motion.button
          whileHover={{ scale: 1.1 }}
          whileTap={{ scale: 0.9 }}
          onClick={nextSlide}
          className="w-14 h-14 rounded-full bg-white/10 backdrop-blur-md border-2 border-white/30 flex items-center justify-center text-white hover:bg-white/20 hover:border-white/50 transition-all duration-300 shadow-lg"
        >
          <ChevronRight className="w-7 h-7" />
        </motion.button>
      </div>
      
      {/* Side Navigation Dots */}
      <div className="absolute right-8 top-1/2 -translate-y-1/2 z-20 flex flex-col gap-4">
        {slides.map((_, index) => (
          <motion.button
            key={index}
            onClick={() => setCurrentSlide(index)}
            className={`w-3 h-3 rounded-full transition-all duration-300 ${
              index === currentSlide 
                ? "bg-yellow-400 scale-150 shadow-lg shadow-yellow-400/50" 
                : "bg-white/30 hover:bg-white/50"
            }`}
            whileHover={{ scale: 1.5 }}
            whileTap={{ scale: 0.8 }}
          />
        ))}
      </div>

      {/* Floating Action Buttons */}
      <motion.div
        initial={{ opacity: 0, x: 50 }}
        animate={{ opacity: 1, x: 0 }}
        transition={{ duration: 0.6, delay: 1.5 }}
        className="absolute right-8 top-1/2 -translate-y-1/2 z-20 flex flex-col gap-4"
      >
        <motion.button
          whileHover={{ scale: 1.1, rotate: 15 }}
          whileTap={{ scale: 0.9 }}
          className="w-12 h-12 rounded-full bg-gradient-to-r from-purple-600 to-blue-600 text-white flex items-center justify-center shadow-lg shadow-purple-600/30"
        >
          <Users className="w-6 h-6" />
        </motion.button>
        <motion.button
          whileHover={{ scale: 1.1, rotate: -15 }}
          whileTap={{ scale: 0.9 }}
          className="w-12 h-12 rounded-full bg-gradient-to-r from-green-600 to-teal-600 text-white flex items-center justify-center shadow-lg shadow-green-600/30"
        >
          <BookOpen className="w-6 h-6" />
        </motion.button>
        <motion.button
          whileHover={{ scale: 1.1, rotate: 15 }}
          whileTap={{ scale: 0.9 }}
          className="w-12 h-12 rounded-full bg-gradient-to-r from-orange-600 to-red-600 text-white flex items-center justify-center shadow-lg shadow-orange-600/30"
        >
          <TrendingUp className="w-6 h-6" />
        </motion.button>
      </motion.div>

      {/* Decorative Bottom Gradient */}
      <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-black/50 to-transparent" />
    </section>
  );
}