import React from "react";
import { Link } from "react-router-dom";
import { createPageUrl } from "@/utils";
import { motion } from "framer-motion";
import { Clock, ArrowRight, BookOpen, Award, Wrench } from "lucide-react";

const programs = [
  {
    title: "Level III Programmes",
    duration: "Maximum 3 Months",
    requirement: "Minimum Grade 9",
    description: "Comprehensive certificate programs in Electrical Engineering, Bricklaying, Metal Fabrication, Food Production, Agriculture, and Fashion Design.",
    image: "https://images.unsplash.com/photo-1581092921461-eab62e97a780?w=800&q=80",
    icon: Award,
    color: "from-blue-500 to-blue-600"
  },
  {
    title: "Skills Award Programmes",
    duration: "Maximum 2 Months",
    requirement: "Open Enrollment",
    description: "Practical skills training in JCB Operations, Grader Operations, Excavator Operations, Computer Studies, Refrigeration, and more.",
    image: "https://images.unsplash.com/photo-1504917595217-d4dc5ebb6122?w=800&q=80",
    icon: Wrench,
    color: "from-green-500 to-green-600"
  },
  {
    title: "Short Courses",
    duration: "1-8 Weeks",
    requirement: "Open Enrollment",
    description: "Intensive training in TV Repair, AC Service, Computer Repair, Social Media Marketing, Software Coding, and Culinary Arts.",
    image: "https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&q=80",
    icon: BookOpen,
    color: "from-purple-500 to-purple-600"
  }
];

export default function ProgramsSection() {
  return (
    <section className="py-24 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <span className="text-[#f5a623] font-semibold text-sm tracking-widest uppercase mb-4 block">
            Our Programs
          </span>
          <h2 className="text-4xl md:text-5xl font-bold text-[#1e3a5f] mb-4">
            2025/2026 Training Programmes
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            TEVETA accredited programs designed to equip you with job-ready skills
          </p>
        </motion.div>

        <div className="grid md:grid-cols-3 gap-8">
          {programs.map((program, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              className="group"
            >
              <div className="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 h-full flex flex-col">
                {/* Image */}
                <div className="relative h-56 overflow-hidden">
                  <img
                    src={program.image}
                    alt={program.title}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
                  <div className={`absolute top-4 left-4 w-14 h-14 bg-gradient-to-br ${program.color} rounded-2xl flex items-center justify-center shadow-lg`}>
                    <program.icon className="w-7 h-7 text-white" />
                  </div>
                  <div className="absolute bottom-4 left-4 right-4">
                    <div className="flex items-center gap-2 text-white/90 text-sm">
                      <Clock className="w-4 h-4" />
                      <span>{program.duration}</span>
                    </div>
                  </div>
                </div>

                {/* Content */}
                <div className="p-6 flex-grow flex flex-col">
                  <h3 className="text-xl font-bold text-[#1e3a5f] mb-2 group-hover:text-[#f5a623] transition-colors">
                    {program.title}
                  </h3>
                  <span className="text-[#f5a623] text-sm font-medium mb-3">
                    {program.requirement}
                  </span>
                  <p className="text-gray-600 leading-relaxed flex-grow">
                    {program.description}
                  </p>
                  <Link
                    to={createPageUrl("Courses")}
                    className="mt-6 inline-flex items-center gap-2 text-[#1e3a5f] font-semibold hover:text-[#f5a623] transition-colors group/link"
                  >
                    View Courses
                    <ArrowRight className="w-4 h-4 group-hover/link:translate-x-1 transition-transform" />
                  </Link>
                </div>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}