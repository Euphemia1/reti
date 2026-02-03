import React, { useState, useEffect, useRef } from "react";
import { Link } from "react-router-dom";
import { createPageUrl } from "@/utils";
import { motion, useScroll, useTransform, useSpring, AnimatePresence } from "framer-motion";
import { Clock, ArrowRight, BookOpen, Award, Wrench, Sparkles, Zap, Target, Rocket, Brain, Cpu, Globe, TrendingUp, Users, Star } from "lucide-react";

// Interactive floating particles
const FloatingParticles = () => {
  const [particles, setParticles] = useState([]);
  
  useEffect(() => {
    const newParticles = [...Array(15)].map((_, i) => ({
      id: i,
      x: Math.random() * 100,
      y: Math.random() * 100,
      size: Math.random() * 4 + 2,
      duration: Math.random() * 20 + 10
    }));
    setParticles(newParticles);
  }, []);
  
  return (
    <div className="absolute inset-0 overflow-hidden pointer-events-none">
      {particles.map((particle) => (
        <motion.div
          key={particle.id}
          className="absolute bg-gradient-to-r from-blue-400/60 to-purple-400/60 rounded-full blur-sm"
          style={{
            width: particle.size + 'px',
            height: particle.size + 'px',
            left: particle.x + '%',
            top: particle.y + '%',
          }}
          animate={{
            x: [0, Math.random() * 100 - 50],
            y: [0, -Math.random() * 100 - 50],
            opacity: [0, 1, 0],
            scale: [0, 1.5, 0],
          }}
          transition={{
            duration: particle.duration,
            repeat: Infinity,
            delay: Math.random() * 5,
            ease: "easeInOut"
          }}
        />
      ))}
    </div>
  );
};

const programs = [
  {
    title: "Level III Programs",
    subtitle: "Advanced Certification",
    duration: "Maximum 3 Months",
    requirement: "Minimum Grade 9",
    description: "Cutting-edge certificate programs in Electrical Engineering, AI & Robotics, Sustainable Architecture, Advanced Manufacturing, Smart Agriculture, and Digital Fashion Design with industry 4.0 integration.",
    image: "/src/photos/20250610_165925.jpg",
    icon: Award,
    color: "from-blue-500 via-indigo-500 to-purple-600",
    features: ["Industry 4.0 Ready", "Global Certification", "AI Integration", "Job Guarantee"],
    stats: { students: "5000+", placement: "95%", salary: "$50k+" }
  },
  {
    title: "Skills Award Programs",
    subtitle: "Professional Excellence",
    duration: "Maximum 2 Months",
    requirement: "Open Enrollment",
    description: "Next-generation skills training in Autonomous Vehicle Operations, Drone Technology, Cybersecurity, Cloud Computing, IoT Development, and Blockchain with Fortune 500 partnerships.",
    image: "/src/photos/20250617_100130.jpg",
    icon: Wrench,
    color: "from-green-500 via-emerald-500 to-teal-600",
    features: ["Hands-on Training", "Expert Mentors", "Live Projects", "Global Recognition"],
    stats: { students: "8000+", certification: "100%", jobs: "2000+" }
  },
  {
    title: "Future Skills Academy",
    subtitle: "Tomorrow's Education Today",
    duration: "1-8 Weeks",
    requirement: "Open Enrollment",
    description: "Revolutionary intensive training in Quantum Computing, Metaverse Development, Biotech Innovation, Space Technology, Renewable Energy Systems, and Advanced AI Research with Silicon Valley collaboration.",
    image: "/src/photos/20250630_151629.jpg",
    icon: Brain,
    color: "from-purple-500 via-pink-500 to-rose-600",
    features: ["Future-Ready", "Innovation Hub", "Research Labs", "Startup Incubator"],
    stats: { students: "3000+", patents: "50", funding: "$10M+" }
  }
];

export default function ProgramsSection() {
  const [hoveredCard, setHoveredCard] = useState(null);
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
  
  const scale = useTransform(scrollYProgress, [0, 1], [0.8, 1.2]);
  const rotate = useTransform(scrollYProgress, [0, 1], [0, 5]);
  return (
    <section ref={containerRef} className="relative py-32 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 overflow-hidden">
      {/* Animated Background */}
      <FloatingParticles />
      
      {/* Grid Pattern */}
      <div className="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Cpath d="M0 40L40 0H20L0 20M40 40V20L20 40"/%3E%3C/g%3E%3C/svg%3E')]" />
      
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
              y: [0, -10, 0],
              rotate: [0, 2, -2, 0],
            }}
            transition={{
              duration: 4,
              repeat: Infinity,
              ease: "easeInOut"
            }}
            className="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600/20 to-blue-600/20 backdrop-blur-md border border-purple-400/30 rounded-full px-8 py-4 mb-8"
          >
            <Sparkles className="w-6 h-6 text-yellow-400" />
            <span className="text-purple-200 font-bold text-lg tracking-wider uppercase">
              Innovation Programs 2025
            </span>
            <Rocket className="w-6 h-6 text-blue-400 animate-bounce" />
          </motion.div>
          
          <motion.h2 
            initial={{ opacity: 0, scale: 0.8 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight"
          >
            <span className="bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
              Future-Ready
            </span>
            <br />
            <span className="text-6xl md:text-8xl font-black text-yellow-400">
              Education
            </span>
          </motion.h2>
          
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.4 }}
            className="text-xl md:text-2xl text-gray-300 max-w-4xl mx-auto leading-relaxed"
          >
            Transform your career with cutting-edge programs designed for the
            <span className="text-yellow-400 font-bold"> digital revolution</span> and
            <span className="text-blue-400 font-bold"> industry 4.0</span>
          </motion.p>
          
          {/* Stats Bar */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.6 }}
            className="flex justify-center gap-12 mt-12"
          >
            {[
              { icon: Users, value: "25,000+", label: "Global Students" },
              { icon: Target, value: "98%", label: "Success Rate" },
              { icon: TrendingUp, value: "500+", label: "Industry Partners" }
            ].map((stat, index) => (
              <motion.div
                key={index}
                initial={{ opacity: 0, scale: 0.8 }}
                whileInView={{ opacity: 1, scale: 1 }}
                viewport={{ once: true }}
                transition={{ duration: 0.4, delay: 0.8 + index * 0.1 }}
                className="text-center"
              >
                <stat.icon className="w-8 h-8 text-yellow-400 mx-auto mb-2" />
                <div className="text-3xl font-bold text-white mb-1">{stat.value}</div>
                <div className="text-sm text-gray-400">{stat.label}</div>
              </motion.div>
            ))}
          </motion.div>
        </motion.div>

        <div className="grid md:grid-cols-3 gap-8">
          {programs.map((program, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 50, scale: 0.9 }}
              whileInView={{ opacity: 1, y: 0, scale: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.8, delay: index * 0.2 }}
              onMouseEnter={() => setHoveredCard(index)}
              onMouseLeave={() => setHoveredCard(null)}
              className="group relative"
            >
              {/* Glow Effect */}
              {hoveredCard === index && (
                <motion.div
                  layoutId="glow"
                  className={`absolute inset-0 bg-gradient-to-r ${program.color} rounded-3xl blur-2xl opacity-50`}
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 0.5 }}
                  exit={{ opacity: 0 }}
                />
              )}
              
              <div className="relative bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl rounded-3xl overflow-hidden shadow-2xl hover:shadow-3xl transition-all duration-500 h-full flex flex-col border border-purple-500/20">
                {/* Animated Header */}
                <div className="relative h-64 overflow-hidden">
                  <motion.img
                    src={program.image}
                    alt={program.title}
                    className="w-full h-full object-cover"
                    animate={{
                      scale: hoveredCard === index ? 1.1 : 1,
                      rotate: hoveredCard === index ? 2 : 0,
                    }}
                    transition={{ duration: 0.6 }}
                  />
                  
                  {/* Gradient Overlay */}
                  <div className={`absolute inset-0 bg-gradient-to-t ${program.color} opacity-80`} />
                  
                  {/* Floating Icon */}
                  <motion.div
                    animate={{
                      y: [0, -10, 0],
                      rotate: [0, 10, -10, 0],
                    }}
                    transition={{
                      duration: 3,
                      repeat: Infinity,
                      ease: "easeInOut"
                    }}
                    className={`absolute top-6 left-6 w-20 h-20 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center shadow-xl border-2 border-white/30`}
                  >
                    <program.icon className="w-10 h-10 text-white" />
                  </motion.div>
                  
                  {/* Duration Badge */}
                  <motion.div
                    animate={{
                      x: hoveredCard === index ? 10 : 0,
                    }}
                    transition={{ duration: 0.3 }}
                    className="absolute bottom-4 left-4 right-4"
                  >
                    <div className="flex items-center gap-2 text-white/90 text-sm font-semibold">
                      <Clock className="w-4 h-4" />
                      <span>{program.duration}</span>
                    </div>
                  </motion.div>
                </div>

                {/* Content */}
                <div className="p-8 flex-grow flex flex-col">
                  {/* Title */}
                  <motion.div
                    animate={{
                      y: hoveredCard === index ? -5 : 0,
                    }}
                    transition={{ duration: 0.3 }}
                  >
                    <h3 className="text-2xl font-bold text-white mb-2 group-hover:text-yellow-400 transition-colors">
                      {program.title}
                    </h3>
                    <p className="text-purple-300 text-sm font-medium mb-4">
                      {program.subtitle}
                    </p>
                  </motion.div>
                  
                  {/* Features */}
                  <div className="mb-6">
                    <div className="flex flex-wrap gap-2">
                      {program.features.map((feature, i) => (
                        <motion.span
                          key={i}
                          initial={{ opacity: 0, scale: 0.8 }}
                          whileInView={{ opacity: 1, scale: 1 }}
                          viewport={{ once: true }}
                          transition={{ duration: 0.3, delay: 0.1 + i * 0.1 }}
                          className={`px-3 py-1 bg-gradient-to-r ${program.color} text-white text-xs font-semibold rounded-full`}
                        >
                          {feature}
                        </motion.span>
                      ))}
                    </div>
                  </div>
                  
                  {/* Description */}
                  <p className="text-gray-300 leading-relaxed mb-6 flex-grow">
                    {program.description}
                  </p>
                  
                  {/* Stats */}
                  <div className="grid grid-cols-3 gap-2 mb-6">
                    {Object.entries(program.stats).map(([key, value], i) => (
                      <motion.div
                        key={key}
                        initial={{ opacity: 0, y: 10 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        viewport={{ once: true }}
                        transition={{ duration: 0.3, delay: 0.2 + i * 0.1 }}
                        className="text-center"
                      >
                        <div className="text-lg font-bold text-yellow-400">{value}</div>
                        <div className="text-xs text-gray-400 capitalize">{key}</div>
                      </motion.div>
                    ))}
                  </div>
                  
                  {/* CTA Button */}
                  <motion.div
                    whileHover={{ scale: 1.05 }}
                    whileTap={{ scale: 0.95 }}
                  >
                    <Link
                      to={createPageUrl("Courses")}
                      className={`group relative overflow-hidden bg-gradient-to-r ${program.color} hover:shadow-2xl text-white px-6 py-4 rounded-2xl font-bold text-lg transition-all duration-500 flex items-center justify-center gap-3 shadow-lg`}
                    >
                      <span className="relative z-10 flex items-center gap-3">
                        <Zap className="w-5 h-5" />
                        Explore Program
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
                  
                  {/* Requirement */}
                  <div className="mt-4 text-center">
                    <span className="text-xs text-gray-400">
                      {program.requirement}
                    </span>
                  </div>
                </div>
              </div>
            </motion.div>
          ))}
        </div>
        
        {/* Bottom CTA */}
        <motion.div
          initial={{ opacity: 0, y: 50 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.8, delay: 0.8 }}
          className="text-center mt-20"
        >
          <motion.div
            animate={{
              scale: [1, 1.05, 1],
              rotate: [0, 1, -1, 0],
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
              className="group relative overflow-hidden bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 hover:from-yellow-500 hover:via-orange-500 hover:to-red-500 text-white px-12 py-6 rounded-3xl font-black text-2xl transition-all duration-500 shadow-2xl shadow-orange-400/30 hover:shadow-orange-400/50 flex items-center gap-4 transform hover:scale-110"
            >
              <span className="relative z-10 flex items-center gap-4">
                <Rocket className="w-8 h-8" />
                Launch Your Future
                <ArrowRight className="w-6 h-6 group-hover:translate-x-3 transition-transform" />
              </span>
              <motion.div
                className="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100"
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