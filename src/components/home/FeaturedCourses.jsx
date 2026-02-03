import React, { useState, useEffect, useRef } from "react";
import { Link } from "react-router-dom";
import { createPageUrl } from "@/utils";
import { motion, useScroll, useTransform, AnimatePresence } from "framer-motion";
import { ArrowRight, Users, Headphones, Thermometer, Truck, Sparkles, Zap, Award, Target, Rocket, Brain, Cpu, Globe, Star, Play, TrendingUp, Shield } from "lucide-react";

// Interactive background animation
const AnimatedBackground = () => {
  return (
    <div className="absolute inset-0 overflow-hidden">
      {[...Array(25)].map((_, i) => (
        <motion.div
          key={i}
          className="absolute bg-gradient-to-r from-blue-400/10 to-purple-400/10 rounded-full blur-lg"
          style={{
            width: Math.random() * 150 + 50 + 'px',
            height: Math.random() * 150 + 50 + 'px',
            left: Math.random() * 100 + '%',
            top: Math.random() * 100 + '%',
          }}
          animate={{
            x: [0, Math.random() * 300 - 150],
            y: [0, Math.random() * 300 - 150],
            scale: [1, 1.3, 1],
            opacity: [0.1, 0.3, 0.1],
            rotate: [0, 180, 360],
          }}
          transition={{
            duration: Math.random() * 15 + 15,
            repeat: Infinity,
            repeatType: "reverse",
            ease: "easeInOut"
          }}
        />
      ))}
    </div>
  );
};

const courses = [
  {
    number: "01",
    title: "AI-Powered Customer Excellence",
    subtitle: "Next-Gen Service Innovation",
    description: "Revolutionary training combining artificial intelligence, emotional intelligence, and advanced communication strategies for the digital age customer experience ecosystem with Fortune 500 integration.",
    image: "/src/photos/20250715_130414.jpg",
    icon: Headphones,
    color: "from-purple-500 via-pink-500 to-rose-600",
    features: ["AI Integration", "Global Standards", "VR Training", "Certification"],
    duration: "6 Weeks",
    level: "Advanced",
    rating: 4.9,
    students: "15,000+"
  },
  {
    number: "02",
    title: "Quantum HVAC Engineering",
    subtitle: "Future-Ready Climate Control",
    description: "Cutting-edge program merging traditional HVAC expertise with quantum computing, IoT sensors, smart home integration, and sustainable energy systems for tomorrow's green buildings.",
    image: "/src/photos/20250716_141148.jpg",
    icon: Thermometer,
    color: "from-blue-500 via-cyan-500 to-teal-600",
    features: ["Quantum Computing", "IoT Integration", "Green Tech", "Smart Systems"],
    duration: "8 Weeks",
    level: "Expert",
    rating: 4.8,
    students: "8,500+"
  },
  {
    number: "03",
    title: "Autonomous Heavy Machinery",
    subtitle: "Industry 4.0 Operations",
    description: "Advanced training in autonomous excavator operations, drone fleet management, AI-powered construction planning, and robotic earth-moving systems with global certification.",
    image: "/src/photos/FB_IMG_1753195579557.jpg",
    icon: Truck,
    color: "from-green-500 via-emerald-500 to-lime-600",
    features: ["Autonomous Systems", "AI Operations", "Drone Integration", "Robotics"],
    duration: "10 Weeks",
    level: "Professional",
    rating: 4.7,
    students: "12,000+"
  }
];

export default function FeaturedCourses() {
  const [hoveredCourse, setHoveredCourse] = useState(null);
  const [mousePosition, setMousePosition] = useState({ x: 0, y: 0 });
  const containerRef = useRef(null);
  const { scrollYProgress } = useScroll({ container: containerRef });

  useEffect(() => {
    const handleMouseMove = (e) => {
      setMousePosition({ x: e.clientX, y: e.clientY });
    };
    window.addEventListener('mousemove', handleMouseMove);
    return () => window.removeEventListener('mousemove', handleMouseMove);
  }, []);

  const scale = useTransform(scrollYProgress, [0, 1], [0.9, 1.1]);
  return (
    <section ref={containerRef} className="relative py-32 bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 overflow-hidden">
      {/* Animated Background */}
      <AnimatedBackground />

      {/* Grid Pattern */}
      <div className="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%23ffffff" fill-opacity="0.02"%3E%3Cpath d="M30 30m-20 0a20 20 0 1 0 40 0a20 20 0 1 0 -40 0"/%3E%3C/g%3E%3C/svg%3E')]'" />

      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 50 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.8 }}
          className="text-center mb-20"
        >
          {/* Floating Badge */}
          <motion.div
            animate={{
              y: [0, -15, 0],
              rotate: [0, 3, -3, 0],
            }}
            transition={{
              duration: 5,
              repeat: Infinity,
              ease: "easeInOut"
            }}
            className="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-600/20 to-purple-600/20 backdrop-blur-md border border-indigo-400/30 rounded-full px-8 py-4 mb-8"
          >
            <Star className="w-6 h-6 text-yellow-400" />
            <span className="text-indigo-200 font-bold text-lg tracking-wider uppercase">
              Featured Excellence Programs
            </span>
            <Zap className="w-6 h-6 text-purple-400 animate-pulse" />
          </motion.div>

          <motion.h2
            initial={{ opacity: 0, scale: 0.8 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight"
          >
            <span className="bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400 bg-clip-text text-transparent">
              Elite Skills
            </span>
            <br />
            <span className="text-6xl md:text-8xl font-black text-yellow-400">
              That Transform
            </span>
          </motion.h2>

          <motion.p
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.4 }}
            className="text-xl md:text-2xl text-gray-300 max-w-4xl mx-auto leading-relaxed"
          >
            Master cutting-edge technologies and
            <span className="text-cyan-400 font-bold"> revolutionary skills</span> that open doors to
            <span className="text-purple-400 font-bold"> global opportunities</span>
          </motion.p>
        </motion.div>

        <div className="space-y-16">
          {courses.map((course, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, x: index % 2 === 0 ? -100 : 100, scale: 0.9 }}
              whileInView={{ opacity: 1, x: 0, scale: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 1, delay: index * 0.3 }}
              onMouseEnter={() => setHoveredCourse(index)}
              onMouseLeave={() => setHoveredCourse(null)}
              className={`flex flex-col ${index % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse'} gap-12 items-center`}
            >
              {/* Image Section */}
              <div className="lg:w-1/2">
                <motion.div
                  whileHover={{ scale: 1.05, rotate: 2 }}
                  transition={{ duration: 0.6 }}
                  className="relative rounded-3xl overflow-hidden shadow-2xl group"
                >
                  <motion.img
                    src={course.image}
                    alt={course.title}
                    className="w-full h-80 lg:h-96 object-cover"
                    animate={{
                      scale: hoveredCourse === index ? 1.1 : 1,
                    }}
                    transition={{ duration: 0.6 }}
                  />

                  {/* Gradient Overlay */}
                  <div className={`absolute inset-0 bg-gradient-to-t ${course.color} opacity-80`} />

                  {/* Floating Number */}
                  <motion.div
                    animate={{
                      y: [0, -10, 0],
                      rotate: [0, 5, -5, 0],
                    }}
                    transition={{
                      duration: 3,
                      repeat: Infinity,
                      ease: "easeInOut"
                    }}
                    className="absolute top-6 left-6 w-24 h-24 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center text-white font-black text-3xl shadow-2xl border-2 border-white/30"
                  >
                    {course.number}
                  </motion.div>

                  {/* Floating Badge */}
                  <motion.div
                    animate={{
                      x: hoveredCourse === index ? 10 : 0,
                    }}
                    transition={{ duration: 0.3 }}
                    className="absolute bottom-6 right-6 bg-white/20 backdrop-blur-md rounded-2xl px-4 py-2 border border-white/30"
                  >
                    <div className="flex items-center gap-2 text-white">
                      <Star className="w-4 h-4 text-yellow-400 fill-current" />
                      <span className="font-bold">{course.rating}</span>
                    </div>
                  </motion.div>

                  {/* Play Button Overlay */}
                  {hoveredCourse === index && (
                    <motion.div
                      initial={{ opacity: 0, scale: 0.8 }}
                      animate={{ opacity: 1, scale: 1 }}
                      className="absolute inset-0 bg-black/50 flex items-center justify-center"
                    >
                      <motion.div
                        whileHover={{ scale: 1.2 }}
                        whileTap={{ scale: 0.9 }}
                        className="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border-2 border-white/50"
                      >
                        <Play className="w-10 h-10 text-white ml-1" />
                      </motion.div>
                    </motion.div>
                  )}
                </motion.div>
              </div>

              {/* Content Section */}
              <div className="lg:w-1/2 lg:px-8">
                <motion.div
                  initial={{ opacity: 0, y: 30 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.6, delay: 0.4 + index * 0.1 }}
                >
                  {/* Icon & Title */}
                  <div className="flex items-center gap-4 mb-6">
                    <motion.div
                      animate={{
                        rotate: [0, 360],
                      }}
                      transition={{
                        duration: 8,
                        repeat: Infinity,
                        ease: "linear"
                      }}
                      className={`w-16 h-16 bg-gradient-to-r ${course.color} rounded-2xl flex items-center justify-center shadow-lg`}
                    >
                      <course.icon className="w-8 h-8 text-white" />
                    </motion.div>
                    <div>
                      <h3 className="text-3xl md:text-4xl font-bold text-white mb-2 group-hover:text-yellow-400 transition-colors">
                        {course.title}
                      </h3>
                      <p className="text-purple-300 text-lg font-medium">
                        {course.subtitle}
                      </p>
                    </div>
                  </div>

                  {/* Features */}
                  <div className="flex flex-wrap gap-2 mb-6">
                    {course.features.map((feature, i) => (
                      <motion.span
                        key={i}
                        initial={{ opacity: 0, scale: 0.8 }}
                        whileInView={{ opacity: 1, scale: 1 }}
                        viewport={{ once: true }}
                        transition={{ duration: 0.3, delay: 0.5 + i * 0.1 }}
                        className={`px-3 py-1 bg-gradient-to-r ${course.color} text-white text-xs font-semibold rounded-full`}
                      >
                        {feature}
                      </motion.span>
                    ))}
                  </div>

                  {/* Description */}
                  <p className="text-gray-300 text-lg leading-relaxed mb-8">
                    {course.description}
                  </p>

                  {/* Stats */}
                  <div className="grid grid-cols-3 gap-4 mb-8">
                    <motion.div
                      initial={{ opacity: 0, y: 20 }}
                      whileInView={{ opacity: 1, y: 0 }}
                      viewport={{ once: true }}
                      transition={{ duration: 0.4, delay: 0.6 }}
                      className="text-center"
                    >
                      <div className="text-2xl font-bold text-yellow-400 mb-1">{course.duration}</div>
                      <div className="text-sm text-gray-400">Duration</div>
                    </motion.div>
                    <motion.div
                      initial={{ opacity: 0, y: 20 }}
                      whileInView={{ opacity: 1, y: 0 }}
                      viewport={{ once: true }}
                      transition={{ duration: 0.4, delay: 0.7 }}
                      className="text-center"
                    >
                      <div className="text-2xl font-bold text-cyan-400 mb-1">{course.level}</div>
                      <div className="text-sm text-gray-400">Level</div>
                    </motion.div>
                    <motion.div
                      initial={{ opacity: 0, y: 20 }}
                      whileInView={{ opacity: 1, y: 0 }}
                      viewport={{ once: true }}
                      transition={{ duration: 0.4, delay: 0.8 }}
                      className="text-center"
                    >
                      <div className="text-2xl font-bold text-purple-400 mb-1">{course.students}</div>
                      <div className="text-sm text-gray-400">Students</div>
                    </motion.div>
                  </div>

                  {/* CTA Button */}
                  <motion.div
                    whileHover={{ scale: 1.05 }}
                    whileTap={{ scale: 0.95 }}
                  >
                    <Link
                      to={createPageUrl("Courses")}
                      className={`group relative overflow-hidden bg-gradient-to-r ${course.color} hover:shadow-2xl text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-500 flex items-center gap-3 shadow-lg`}
                    >
                      <span className="relative z-10 flex items-center gap-3">
                        <Rocket className="w-5 h-5" />
                        Master This Course
                        <ArrowRight className="w-5 h-5 group-hover:translate-x-2 transition-transform" />
                      </span>
                      <motion.div
                        className="absolute inset-0 bg-white opacity-0 group-hover:opacity-20"
                        initial={{ x: "-100%" }}
                        whileHover={{ x: "100%" }}
                        transition={{ duration: 0.5 }}
                      />
                    </Link>
                  </motion.div>
                </motion.div>
              </div>
            </motion.div>
          ))}
        </div>

        {/* Bottom CTA */}
        <motion.div
          initial={{ opacity: 0, y: 50 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.8, delay: 1.0 }}
          className="text-center mt-20"
        >
          <motion.div
            animate={{
              scale: [1, 1.05, 1],
              rotate: [0, 2, -2, 0],
            }}
            transition={{
              duration: 4,
              repeat: Infinity,
              ease: "easeInOut"
            }}
            className="inline-block"
          >
            <Link
              to={createPageUrl("Courses")}
              className="group relative overflow-hidden bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400 hover:from-cyan-500 hover:via-blue-500 hover:to-purple-500 text-white px-16 py-8 rounded-3xl font-black text-2xl transition-all duration-500 shadow-2xl shadow-cyan-400/30 hover:shadow-cyan-400/50 flex items-center gap-4 transform hover:scale-110"
            >
              <span className="relative z-10 flex items-center gap-4">
                <Brain className="w-10 h-10" />
                Explore All Elite Programs
                <ArrowRight className="w-8 h-8 group-hover:translate-x-4 transition-transform" />
              </span>
              <motion.div
                className="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-400 opacity-0 group-hover:opacity-100"
                initial={{ x: "-100%" }}
                whileHover={{ x: "100%" }}
                transition={{ duration: 0.6 }}
              />
            </Link>
          </motion.div>
        </motion.div>
      </div>
    </section>
  );
}