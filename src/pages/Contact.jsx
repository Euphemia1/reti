import React, { useState } from "react";
import { motion } from "framer-motion";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { base44 } from "@/api/base44Client";
import { 
  Send, 
  CheckCircle, 
  Loader2, 
  MapPin, 
  Phone, 
  Mail, 
  Clock,
  Download,
  FileText,
  BookOpen
} from "lucide-react";

export default function Contact() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    subject: "",
    message: ""
  });
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    
    await base44.entities.ContactMessage.create(formData);
    
    setSuccess(true);
    setFormData({ name: "", email: "", phone: "", subject: "", message: "" });
    setLoading(false);
    
    setTimeout(() => setSuccess(false), 5000);
  };

  const contactInfo = [
    {
      icon: MapPin,
      title: "Visit Us",
      lines: ["East Campus, Plot A150", "Off Munali Road", "P.O Box 33381, Lusaka, Zambia"]
    },
    {
      icon: Phone,
      title: "Call Us",
      lines: ["0211-296071", "0954-999900", "0964-082013"]
    },
    {
      icon: Mail,
      title: "Email Us",
      lines: ["academic@reti.edu.zm"]
    },
    {
      icon: Clock,
      title: "Office Hours",
      lines: ["Monday - Friday", "8:00 AM - 5:00 PM"]
    }
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Hero */}
      <section className="relative h-[40vh] min-h-[300px] overflow-hidden">
        <div 
          className="absolute inset-0 bg-cover bg-center"
          style={{ 
            backgroundImage: "url(/src/photos/news_1.jpg)"
          }}
        />
        <div className="absolute inset-0 bg-gradient-to-r from-[#1e3a5f]/95 via-[#1e3a5f]/80 to-[#1e3a5f]/60" />
        
        <div className="relative z-10 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            className="text-center"
          >
            <h1 className="text-5xl md:text-6xl font-bold text-white mb-4">
              Contact <span className="text-[#f5a623]">Us</span>
            </h1>
            <p className="text-xl text-gray-300 max-w-2xl mx-auto">
              Get in touch with us for inquiries, applications, or any questions
            </p>
          </motion.div>
        </div>
      </section>

      {/* Main Content */}
      <section className="py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-3 gap-12">
            {/* Contact Form */}
            <div className="lg:col-span-2">
              <motion.div
                initial={{ opacity: 0, y: 30 }}
                animate={{ opacity: 1, y: 0 }}
                className="bg-white rounded-3xl shadow-xl p-8 md:p-12"
              >
                <h2 className="text-3xl font-bold text-[#1e3a5f] mb-2">
                  Send Us a Message
                </h2>
                <p className="text-gray-600 mb-8">
                  Fill out the form below and we'll get back to you as soon as possible
                </p>

                {success ? (
                  <div className="text-center py-12">
                    <div className="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                      <CheckCircle className="w-10 h-10 text-green-500" />
                    </div>
                    <h3 className="text-2xl font-bold text-[#1e3a5f] mb-2">
                      Message Sent Successfully!
                    </h3>
                    <p className="text-gray-600">
                      We'll get back to you within 24 hours.
                    </p>
                  </div>
                ) : (
                  <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid md:grid-cols-2 gap-6">
                      <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                          Full Name *
                        </label>
                        <Input
                          value={formData.name}
                          onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                          placeholder="Your full name"
                          required
                          className="h-12 rounded-xl border-gray-200 focus:border-[#f5a623] focus:ring-[#f5a623]"
                        />
                      </div>
                      <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                          Email Address *
                        </label>
                        <Input
                          type="email"
                          value={formData.email}
                          onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                          placeholder="your@email.com"
                          required
                          className="h-12 rounded-xl border-gray-200 focus:border-[#f5a623] focus:ring-[#f5a623]"
                        />
                      </div>
                    </div>
                    
                    <div className="grid md:grid-cols-2 gap-6">
                      <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                          Phone Number
                        </label>
                        <Input
                          value={formData.phone}
                          onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                          placeholder="+260..."
                          className="h-12 rounded-xl border-gray-200 focus:border-[#f5a623] focus:ring-[#f5a623]"
                        />
                      </div>
                      <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                          Subject
                        </label>
                        <Input
                          value={formData.subject}
                          onChange={(e) => setFormData({ ...formData, subject: e.target.value })}
                          placeholder="How can we help?"
                          className="h-12 rounded-xl border-gray-200 focus:border-[#f5a623] focus:ring-[#f5a623]"
                        />
                      </div>
                    </div>
                    
                    <div>
                      <label className="block text-sm font-medium text-gray-700 mb-2">
                        Message *
                      </label>
                      <Textarea
                        value={formData.message}
                        onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                        placeholder="Tell us more about your inquiry..."
                        required
                        rows={6}
                        className="rounded-xl border-gray-200 focus:border-[#f5a623] focus:ring-[#f5a623] resize-none"
                      />
                    </div>
                    
                    <Button
                      type="submit"
                      disabled={loading}
                      className="w-full h-14 bg-[#f5a623] hover:bg-[#e09515] text-white rounded-xl font-semibold text-lg shadow-lg shadow-[#f5a623]/30 hover:shadow-xl transition-all duration-300"
                    >
                      {loading ? (
                        <Loader2 className="w-5 h-5 animate-spin" />
                      ) : (
                        <>
                          <Send className="w-5 h-5 mr-2" />
                          Send Message
                        </>
                      )}
                    </Button>
                  </form>
                )}
              </motion.div>
            </div>

            {/* Sidebar */}
            <div className="space-y-8">
              {/* Contact Info */}
              <motion.div
                initial={{ opacity: 0, x: 30 }}
                animate={{ opacity: 1, x: 0 }}
                className="bg-white rounded-3xl shadow-xl p-8"
              >
                <h3 className="text-2xl font-bold text-[#1e3a5f] mb-6">
                  Contact Information
                </h3>
                <div className="space-y-6">
                  {contactInfo.map((info, index) => (
                    <div key={index} className="flex items-start gap-4">
                      <div className="w-12 h-12 bg-[#f5a623]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                        <info.icon className="w-6 h-6 text-[#f5a623]" />
                      </div>
                      <div>
                        <h4 className="font-semibold text-[#1e3a5f] mb-1">{info.title}</h4>
                        {info.lines.map((line, i) => (
                          <p key={i} className="text-gray-600 text-sm">{line}</p>
                        ))}
                      </div>
                    </div>
                  ))}
                </div>
              </motion.div>

              {/* Downloads */}
              <motion.div
                initial={{ opacity: 0, x: 30 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 0.1 }}
                className="bg-gradient-to-br from-[#1e3a5f] to-[#2d5a8a] rounded-3xl shadow-xl p-8 text-white"
              >
                <h3 className="text-2xl font-bold mb-6 flex items-center gap-2">
                  <Download className="w-6 h-6 text-[#f5a623]" />
                  Downloads
                </h3>
                <div className="space-y-4">
                  <a
                    href="https://reti-edu.web.app/downloads/RISING%20EAST%20TRAINING%20INSTITUTE%20APPLICATION%20FORM%202025-2026.pdf"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="flex items-center gap-3 bg-white/10 rounded-xl p-4 hover:bg-white/20 transition-colors group"
                  >
                    <FileText className="w-8 h-8 text-[#f5a623]" />
                    <div>
                      <p className="font-semibold group-hover:text-[#f5a623] transition-colors">Application Form</p>
                      <p className="text-sm text-gray-300">PDF Download</p>
                    </div>
                  </a>
                  <a
                    href="https://reti-edu.web.app/downloads/RETI_Courses_2025_2026.pdf"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="flex items-center gap-3 bg-white/10 rounded-xl p-4 hover:bg-white/20 transition-colors group"
                  >
                    <BookOpen className="w-8 h-8 text-[#f5a623]" />
                    <div>
                      <p className="font-semibold group-hover:text-[#f5a623] transition-colors">Course List 2025-2026</p>
                      <p className="text-sm text-gray-300">PDF Download</p>
                    </div>
                  </a>
                </div>
              </motion.div>
            </div>
          </div>
        </div>
      </section>

      {/* Join Courses CTA */}
      <section className="py-16 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
          >
            <h2 className="text-3xl md:text-4xl font-bold text-[#1e3a5f] mb-6">
              Join Our Courses
            </h2>
            <p className="text-lg text-gray-600 mb-8 leading-relaxed max-w-2xl mx-auto">
              At Rising East Training Institute (RETI), we believe in empowering individuals with practical skills that open doors to real opportunities. Our programs are designed to equip you with hands-on knowledge, industry-relevant expertise, and the confidence to succeed in today's competitive job market.
            </p>
            <p className="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
              Whether you are starting your career, upgrading your skills, or exploring a new path, RETI provides a supportive environment with experienced instructors and modern facilities. Take the step today—invest in your future with a skill that matters.
            </p>
          </motion.div>
        </div>
      </section>
    </div>
  );
}